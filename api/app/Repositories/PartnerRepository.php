<?php

namespace App\Repositories;

use App\Models\Partner;
use App\Models\PartnerContactPerson;
use App\Services\FileServices;
use Illuminate\Support\Facades\DB;

class PartnerRepository
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

        $partner = Partner::create([
            'name' => $data['name'],
            'address' => $data['address']
        ]);

        if (isset($data['contactPersons'])) {
            foreach ($data['contactPersons'] as $person) {
                if ($person['name'] != null) {
                    $person['partner_id'] = $partner->id;
                    PartnerContactPerson::create($person);
                }
            }
        }

        if ( isset($data['image']) ) {
            $imageFileName = rand(1000000, 99999999999) . '_partner_' . $partner->id . '_image.' . strtolower($data['image']->getClientOriginalExtension());

            $path = $this->fileServices->savePhoto(1000, $data['image'], 'partners', $imageFileName);

            Partner::where('id', $partner->id)->update([
                'image' => $path
            ]);
        }

        DB::commit();

        return $partner;
    }

    /**
     * update
     *
     * @param  mixed $partner
     * @param  mixed $data
     * @return void
     */
    public function update ($partner, $data)
    {
        DB::beginTransaction();

        if ( isset($data['contactPersons']) ) {
            foreach ($data['contactPersons'] as $person) {
                if (isset($person['id'])) {
                    PartnerContactPerson::where('id', $person['id'])->update($person);
                } else {
                    if ($person['name'] != null) {
                        $person['partner_id'] = $partner->id;
                        PartnerContactPerson::create($person);
                    }
                }
            }
        }

        $partner->update([
            'name' => $data['name'],
            'address' => $data['address']
        ]);

        if ( isset($data['image']) ) {
            $imageFileName = rand(1000000, 99999999999) . '_partner_' . $partner->id . '_image.' . strtolower($data['image']->getClientOriginalExtension());

            $path = $this->fileServices->savePhoto(1000, $data['image'], 'partners', $imageFileName);

            $partner->update([
                'image' => $path
            ]);
        }

        DB::commit();

        return $partner;
    }

    /**
     * delete
     *
     * @param  mixed $category
     * @return void
     */
    public function delete($partner)
    {
        DB::beginTransaction();

        $partner->delete();

        DB::commit();

        return $partner;
    }

    /**
     * deletePerson
     *
     * @param  mixed $id
     * @return void
     */
    public function deletePerson(string $id)
    {
        DB::beginTransaction();
        $person = PartnerContactPerson::where('id', $id)->delete();
        DB::commit();

        return $person;
    }
}
