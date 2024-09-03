<?php

namespace App\Repositories;

use App\Models\Opportunity;
use App\Services\FileServices;
use Illuminate\Support\Facades\DB;
use App\Models\OpportunityAttachments;
use App\Models\OpportunityTasks;

class OpportunityRepository
{
    /**
     * fileServices
     *
     * @var mixed
     */
    private $fileServices;

    /**
     * __construct
     *
     * @param  mixed $fileServices
     * @return void
     */
    public function __construct(
        FileServices $fileServices
    ) {
        $this->fileServices = $fileServices;
    }

    /**
     * create
     *
     * @param  mixed $data
     * @return void
     */
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $opportunity = Opportunity::create($data);
            DB::commit();
            return $opportunity;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * updateStatus
     *
     * @param  string $opportunityId
     * @param  string $statusId
     * @return void
     */
    public function updateStatus(string $opportunityId, string $statusId)
    {

        DB::beginTransaction();
        try {
            $opportunity = Opportunity::find($opportunityId)->update([
                'opportunity_statuses_id' => $statusId
            ]);

            DB::commit();
            return [
                'status' => 200,
                'message' => "Opportunity updeted successfully",
                'data' => $opportunity,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 400,
                'message' => "Something went wrong",
                'data' => $e->getMessage(),
            ];
        }

    }

    /**
     * attachFile
     *
     * @param  mixed $data
     * @return void
     */
    public function attachFile($data)
    {
        DB::beginTransaction();

        try {
            $file = [
                'opportunity_id' => $data['opportunity'],
                'status_id' => $data['status']
            ];
            $attachment = OpportunityAttachments::create($file);
            $ext = $data['file']->getClientOriginalExtension();

            if ( isset($data['file']) ) {

                $imageFileName = rand(1000000, 99999999999) . '_opportunity_' . $attachment->id . '_image.' . strtolower($ext);

                $path = $this->fileServices->saveFile($data['file'], 'opportunities/' . $data['opportunity'], $imageFileName);

                OpportunityAttachments::where('id', $attachment->id)->update([
                    'fileName' => $path,
                    'fileType' => $ext
                ]);
            }

            DB::commit();
            return $file;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * deleteFile
     *
     * @param  mixed $data
     * @return void
     */
    public function deleteFile(string $id)
    {
        DB::beginTransaction();
        try {
            $file = OpportunityAttachments::find($id);

            $url = 'public/' . $file->fileName;

            $this->fileServices->deleteFile($url);
            $file->delete();
            DB::commit();
            return [
                'status' => 200,
                'message' => "Opportunity file deleted successfully",
                'data' => $file,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 400,
                'message' => "Something went wrong",
                'data' => $e->getMessage(),
            ];
        }
    }

    /**
     * addTask
     *
     * @param  mixed $data
     * @return void
     */
    public function addTask($data)
    {
        DB::beginTransaction();

        try {
            $task = OpportunityTasks::create($data);

            DB::commit();
            return $task;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * completeTask
     *
     * @param  mixed $taskId
     * @param  mixed $update
     * @return void
     */
    public function completeTask($taskId, $update)
    {
        DB::beginTransaction();

        try {
            $task = OpportunityTasks::find($taskId);
            $task->complete_date = $update['complete_date'];
            $task->save();

            DB::commit();
            return [
                'status' => 200,
                'message' => "Opportunity task updated successfully",
                'data' => $task,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 400,
                'message' => "Something went wrong",
                'data' => $e->getMessage(),
            ];
        }
    }

    /**
     * deleteTask
     *
     * @param  mixed $data
     * @return void
     */
    public function deleteTask(string $id)
    {
        DB::beginTransaction();
        try {
            $task = OpportunityTasks::find($id);
            $task->delete();

            DB::commit();
            return [
                'status' => 200,
                'message' => "Opportunity task deleted successfully",
                'data' => $task,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 400,
                'message' => "Something went wrong",
                'data' => $e->getMessage(),
            ];
        }
    }

}
