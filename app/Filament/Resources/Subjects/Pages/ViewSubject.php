<?php

namespace App\Filament\Resources\Subjects\Pages;

use App\Filament\Resources\Subjects\SubjectResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSubject extends ViewRecord
{
    protected static string $resource = SubjectResource::class;
    protected string $view = 'filament.resources.subjects.pages.view-subject';
}
