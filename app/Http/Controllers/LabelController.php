<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use App\Models\Label;
use Illuminate\Http\JsonResponse;
use \Illuminate\Http\Request;

class LabelController extends Controller
{

    public Label $label;

    public function __construct(Label $label)
    {
        $this->label = $label;
    }

    /**
     * Create a new label.
     */
    public function createLabel(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $label = new Label;
        $label->name = $request->name;

        if ($label->save()) {
            return response()->json([
                'message' => "Label successfully created.",
                'success' => true
            ], 201);
        }

        return response()->json([
            'message' => "An error occurred. Label not created.",
            'success' => false
        ], 500);
    }

    /**
     * Get all labels.
     */
//    public function getAllLabels(Label $label): JsonResponse
//    {
//        //
//    }

    /**
     * Display the specified label.
     */
//    public function getLabel(Label $label): JsonResponse
//    {
//        //
//    }

    /**
     * Edit the specified label.
     */
//    public function editLabel(Label $label): JsonResponse
//    {
//        //
//    }

    /**
     * Remove the specified label.
     */
//    public function deleteLabel(Label $label): JsonResponse
//    {
//        //
//    }
}
