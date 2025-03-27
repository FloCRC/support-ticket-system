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
    public function getAllLabels(): JsonResponse
    {
        $labels = $this->label->all();

        if ($labels->isEmpty()) {
            return response()->json([
                'message' => 'No labels found.',
                'success' => false,
            ], 404);
        }

        return response()->json([
            'message' => "Labels successfully retrieved.",
            'success' => true,
            'data' => $labels
        ]);
    }

    /**
     * Display the specified label.
     */
    public function getLabel(int $labelId): JsonResponse
    {
        $label = $this->label->find($labelId);

        if (!$label) {
            return response()->json([
                'message' => 'Label not found.',
                'success' => false,
            ], 404);
        }

        return response()->json([
            'message' => "Label successfully retrieved.",
            'success' => true,
            'data' => $label
        ]);
    }

    /**
     * Edit the specified label.
     */
    public function editLabel(int $labelId, Request $request): JsonResponse
    {
        $label = $this->label->find($labelId);

        if (!$label) {
            return response()->json([
                'message' => 'Label not found.',
                'success' => false,
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $label->name = $request->name;

        if ($label->save()) {
            return response()->json([
                'message' => "Label successfully edited.",
                'success' => true
            ]);
        }

        return response()->json([
            'message' => "An error occurred. Label not updated.",
            'success' => false,
        ], 500);
    }

    /**
     * Remove the specified label.
     */
    public function deleteLabel(int $labelId): JsonResponse
    {
        $label = $this->label->find($labelId);

        if (!$label) {
            return response()->json([
                'message' => 'Label not found.',
                'success' => false,
            ], 404);
        }

        if ($label->delete()) {
            return response()->json([
                'message' => "Label successfully deleted.",
                'success' => true
            ]);
        }

        return response()->json([
            'message' => "An error occurred. Label not deleted.",
            'success' => false,
        ], 500);
    }
}
