<?php

namespace Manmohanjit\BelongsToDependency;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class BelongsToDependency extends BelongsTo
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'belongs-to-dependency';

    /**
     * Build an associatable query for the field.
     * Here is where we add the depends on value and filter results
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  bool  $withTrashed
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildAssociatableQuery(NovaRequest $request, $withTrashed = false)
    {
        $query = parent::buildAssociatableQuery($request, $withTrashed);

        if ($request->has('dependsOnValue')) {
            $query->where($this->meta['dependsOnKey'], $request->dependsOnValue);
        } else {
            $query->where($this->meta['dependsOnKey'], '');
        }

        return $query;
    }

    /**
     * Set the depends on field and depends on key
     *
     * @param  string $dependsOnField
     * @param  string $tableKey
     * @return $this
     */
    public function dependsOn($dependsOnField, $tableKey)
    {
        return $this->withMeta([
            'dependsOn' => $dependsOnField,
            'dependsOnKey' => $tableKey,
        ]);
    }
}
