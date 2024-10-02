<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\SubCriteria;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class WeightCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Criteria::all();

        return view('pages.criteria.index', [
            'title' => 'Bobot Kriteria',
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.criteria.create', [
            'title' => 'Tambah Bobot Kriteria',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validation = $request->validate([
                'name' => 'required|string|regex:/^[a-zA-Z0-9]+[a-zA-Z0-9 .,!-]*$/|max:255|unique:criterias,name',
                'weight' => 'required|min:1|max:6',
                'type' => [
                    'required',
                    Rule::in(['Benefit', 'Cost']),
                ],
                'sub_criterias' => [
                    'required',
                    Rule::in(['Iya', 'Tidak']),
                ],
            ], $this->messageValidation(), $this->attributeValidation());

            $validation['status'] = 'Aktif';
            $criteriaCount = Criteria::count();

            $totalWeight = Criteria::whereNot('name', 'Program Studi')->sum('weight') * 100;

            if (($totalWeight + $validation['weight']) > 100) {
                return response()->json([
                    'errors' => 'Criteria Weight Value exceeds 100%',
                ], 424);
            }

            if ($criteriaCount == 0) {
                $validation['code'] = 'C1';
            } else {
                $lastCriteria = Criteria::orderBy('id', 'desc')->first();
                $codeNumber = (int) substr($lastCriteria->code, 1);
                $nextCodeNumber = $codeNumber + 1;
                $validation['code'] = 'C' . $nextCodeNumber;
            }

            $validation['weight'] = number_format(round($validation['weight'] / 100, 2), 2, '.', '');

            if ($validation['sub_criterias'] == 'Tidak') {
                $data = Criteria::create($validation);
            }

            $val = $validation;

            if ($request->sub_criterias === 'Iya') {
                $request->validate([
                    'name_sub' => 'required',
                    'name_sub.*' => 'required',
                    'value' => 'required',
                    'value.*' => 'required|min:1|max:6',
                ], $this->messageValidation(), $this->attributeValidation());

                $names = $request->name_sub;
                $values = $request->value;

                foreach ($values as $value) {
                    if ($value > 100) {
                        return response()->json([
                            'errors' => 'Sub Criteria Value exceeds 100.'
                        ], 425);
                    }
                }

                if (count($names) === count($values)) {
                    $data = Criteria::create($val);

                    foreach ($names as $key => $name) {
                        SubCriteria::create([
                            'name_sub' => $name,
                            'value' => number_format(round($values[$key] / 100, 2), 2, '.', ''),
                            'criteria_id' => $data->id,
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to register the sub criteria.'
                    ], 500);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Criteria created successfully',
                'data' => $data,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);

            $criteria = Criteria::findOrFail($id);
            $sub_criteria = SubCriteria::where('criteria_id', $id)->get();

            return view('pages.criteria.show_criteria', [
                'title' => 'Detail Bobot Kriteria',
                'data' => $sub_criteria,
                'criteria' => $criteria
            ]);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);

            $data = Criteria::findOrFail($id);
            $sub_criteria = SubCriteria::where('criteria_id', $id)->get();

            return view('pages.criteria.update', [
                'title' => 'Ubah Bobot Kriteria',
                'data' => $data,
                'sub_criteria' => $sub_criteria
            ]);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validation = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z0-9]+[a-zA-Z0-9 .,!-]*$/',
                    'max:255',
                    Rule::unique('criterias', 'name')->ignore($id),
                ],
                'weight' => 'required|min:1|max:6',
                'type' => [
                    'required',
                    'sometimes',
                    Rule::in(['Benefit', 'Cost']),
                ],
                'sub_criterias' => [
                    'required',
                    'sometimes',
                    Rule::in(['Iya', 'Tidak']),
                ],
                'code' => 'sometimes',
            ], $this->messageValidation(), $this->attributeValidation());

            $validation['status'] = 'Aktif';

            $totalWeight = Criteria::where('id', '!=', $id)->whereNot('name', 'Program Studi')->sum('weight') * 100;

            if (($totalWeight + $validation['weight']) > 100) {
                return response()->json([
                    'errors' => 'Criteria Weight Value exceeds 100%',
                ], 424);
            }

            $validation['weight'] = number_format(round($validation['weight'] / 100, 2), 2, '.', '');

            if ($validation['sub_criterias'] == 'Tidak') {
                $data = Criteria::findOrFail($id)->update($validation);
            }

            $val = $validation;

            if ($request->sub_criterias === 'Iya') {
                $request->validate([
                    'id_sub.*' => 'sometimes',
                    'name_sub' => 'required',
                    'name_sub.*' => 'required',
                    'value' => 'required',
                    'value.*' => 'required|min:1|max:6',
                ], $this->messageValidation(), $this->attributeValidation());

                $names = $request->name_sub;
                $values = $request->value;

                foreach ($values as $value) {
                    if ($value > 100) {
                        return response()->json([
                            'errors' => 'Sub Criteria Value exceeds 100.'
                        ], 425);
                    }
                }

                if (count($names) === count($values)) {
                    $criteria = Criteria::findOrFail($id);
                    $criteria->update($val);

                    $subCriteriaInDB = SubCriteria::where('criteria_id', $id)->get();

                    foreach ($subCriteriaInDB as $deleteSubCriteria) {
                        if (!in_array($deleteSubCriteria->id, $request->id_sub)) {
                            $deleteSubCriteria->delete();
                        }
                    }

                    foreach ($names as $key => $name) {
                        $subCriteriaId = isset($request->id_sub[$key]) ? $request->id_sub[$key] : null;

                        if ($subCriteriaId) {
                            SubCriteria::where('id', $subCriteriaId)->update([
                                'name_sub' => $name,
                                'value' => number_format(round($values[$key] / 100, 2), 2, '.', '')
                            ]);
                        } else {
                            SubCriteria::firstOrCreate(
                                ['criteria_id' => $id, 'name_sub' => $name],
                                ['value' => number_format(round($values[$key] / 100, 2), 2, '.', '')]
                            );
                        }
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Sub criteria updated successfully.',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to update sub criteria.'
                    ], 500);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Criteria updated successfully',
                'data' => $data,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function toggleStatus(Criteria $criteria)
    {
        $criteria->status = ($criteria->status === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $criteria->save();

        return response()->json([
            'status' => $criteria->status
        ]);
    }

    public function editCriteria()
    {
        try {
            $data = Criteria::all();

            return view('pages.criteria.update', [
                'title' => 'Ubah Bobot Kriteria',
                'data' => $data
            ]);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    private function messageValidation()
    {
        $message = [
            'required' => ':attribute harus diisi.',
            'string' => ':attribute harus berupa teks.',
            'regex' => 'Format :attribute tidak valid.',
            'unique' => ':attribute sudah digunakan.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'exists' => ':attribute yang dipilih tidak valid.',
        ];

        return $message;
    }

    private function attributeValidation()
    {
        $customAttributes = [
            'name' => 'Kriteria',
            'weight' => 'Bobot Kriteria',
            'name_sub' => 'Sub Kriteria',
            'value' => 'Nilai Sub Kriteria',
            'type' => 'Jenis Kriteria',
            'sub_criterias' => 'Status Sub Kriteria',
        ];

        return $customAttributes;
    }
}
