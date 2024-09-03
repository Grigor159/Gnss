<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\OpportunityAttachments;
use App\Repositories\CustomerRepository;
use App\Repositories\OpportunityRepository;

class OpportunityService
{
    /**
     * opportunityRepository
     *
     * @var mixed
     */
    private $opportunityRepository;

    /**
     * opportunityNoteService
     *
     * @var mixed
     */
    private $opportunityNoteService;

    /**
     * __construct
     *
     * @param  OpportunityRepository $opportunityRepository
     * @param  OpportunityNotesService $opportunityNoteService
     * @return void
     */
    public function __construct(
        OpportunityRepository $opportunityRepository,
        OpportunityNotesService $opportunityNoteService
    ) {
        $this->opportunityRepository = $opportunityRepository;
        $this->opportunityNoteService = $opportunityNoteService;
    }

    /**
     * create
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data)
    {
        if (isset($data['note'])) {
            $note = [
                'text' => $data['note'],
                'status_id' => 1
            ];
            unset($data['note']);
        }

        $opportunity = $this->opportunityRepository->create($data);

        if (isset($note)) {
            $note['opportunity_id'] = $opportunity->id;
            $this->opportunityNoteService->create($note);
        }

        return $opportunity;
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
        return $this->opportunityRepository->updateStatus($opportunityId, $statusId);
    }

    /**
     * addNote
     *
     * @param  mixed $data
     * @return void
     */
    public function addNote($data)
    {
        $note = [
            'opportunity_id' => $data['opportunity'],
            'text' => $data['note'],
            'status_id' => $data['status']
        ];

        return $this->opportunityNoteService->create($note);
    }

    /**
     * attachFile
     *
     * @param  mixed $data
     * @return void
     */
    public function attachFile($data)
    {
        return $this->opportunityRepository->attachFile($data);
    }

    /**
     * deleteFile
     *
     * @param  mixed $data
     * @return void
     */
    public function deleteFile($data)
    {
        return $this->opportunityRepository->deleteFile($data);
    }

    /**
     * addTask
     *
     * @param  mixed $data
     * @return void
     */
    public function addTask($data)
    {
        $task = [
            'opportunity_id' => $data['opportunity'],
            'title' => $data['title'],
            'status_id' => $data['status'],
            'complete_date' => null
        ];

        return $this->opportunityRepository->addTask($task);
    }

    /**
     * completeTask
     *
     * @param  mixed $data
     * @return void
     */
    public function completeTask($data)
    {
        $update = [
            'complete_date' => $data['done'] == 'true' ? date('Y-m-d H:i:s') : null
        ];

        return $this->opportunityRepository->completeTask($data['id'], $update);
    }

    /**
     * deleteTask
     *
     * @param  mixed $data
     * @return void
     */
    public function deleteTask($data)
    {
        return $this->opportunityRepository->deleteTask($data);
    }

    /**
     * closeOpportunity
     *
     * @param  mixed $data
     * @return void
     */
    public function closeOpportunity($data)
    {
        return  $this->opportunityRepository->updateStatus($data['id'], $data['status']);
    }

}
