<?php

namespace Fintech\Bell\Services;

use Fintech\Bell\Interfaces\TemplateRepository;

/**
 * Class TemplateService
 */
class TemplateService
{
    /**
     * TemplateService constructor.
     */
    public function __construct(private readonly TemplateRepository $templateRepository) {}

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->templateRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->templateRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->templateRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->templateRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->templateRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->templateRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->templateRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->templateRepository->create($filters);
    }
}
