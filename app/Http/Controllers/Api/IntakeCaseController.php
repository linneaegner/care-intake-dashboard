<?php

namespace App\Http\Controllers\Api;

use App\Enums\CaseStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIntakeCaseRequest;
use App\Http\Requests\UpdateIntakeCaseNoteRequest;
use App\Http\Requests\UpdateIntakeCaseStatusRequest;
use App\Http\Resources\IntakeCaseResource;
use App\Models\IntakeCase;
use App\Services\IntakeCaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IntakeCaseController extends Controller
{
    public function __construct(
        private readonly IntakeCaseService $intakeCaseService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $query = IntakeCase::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('alias', 'like', '%'.$search.'%')
                    ->orWhere('case_type', 'like', '%'.$search.'%');
            });
        }

        return IntakeCaseResource::collection($query->get());
    }

    public function store(StoreIntakeCaseRequest $request): IntakeCaseResource
    {
        $intakeCase = $this->intakeCaseService->create($request->validated());

        return new IntakeCaseResource($intakeCase);
    }

    public function show(IntakeCase $intakeCase): IntakeCaseResource
    {
        return new IntakeCaseResource($intakeCase->load('events'));
    }

    public function updateStatus(
        UpdateIntakeCaseStatusRequest $request,
        IntakeCase $intakeCase,
    ): IntakeCaseResource {
        $intakeCase = $this->intakeCaseService->updateStatus(
            $intakeCase,
            $request->enum('status', CaseStatus::class),
        );

        return new IntakeCaseResource($intakeCase);
    }

    public function updateNote(
        UpdateIntakeCaseNoteRequest $request,
        IntakeCase $intakeCase,
    ): IntakeCaseResource {
        $intakeCase = $this->intakeCaseService->updateNote(
            $intakeCase,
            $request->validated('internal_note'),
        );

        return new IntakeCaseResource($intakeCase);
    }
}
