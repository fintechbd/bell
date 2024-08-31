<?php

namespace Fintech\Bell\Services;

use Fintech\Bell\Interfaces\TriggerVariableRepository;

/**
 * Class TriggerVariableService
 */
class TriggerVariableService
{
    /**
     * TriggerVariableService constructor.
     */
    public function __construct(public TriggerVariableRepository $triggerVariableRepository) {}

    public function find($id, $onlyTrashed = false)
    {
        return $this->triggerVariableRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->triggerVariableRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->triggerVariableRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->triggerVariableRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->triggerVariableRepository->list($filters);
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->triggerVariableRepository->list($filters);

    }

    public function import(array $filters)
    {
        return $this->triggerVariableRepository->create($filters);
    }

    public function create(array $inputs = [])
    {
        return $this->triggerVariableRepository->create($inputs);
    }
}
