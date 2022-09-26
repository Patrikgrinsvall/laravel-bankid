<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Http\Requests\NovaRequest;

class BankidIntegration extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\BankidIntegration::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'label';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = ['label'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make('id')->sortable(),

            Text::make('Label')
                ->rules('required', 'max:255', 'string')
                ->placeholder('Label'),

            Textarea::make('Description')
                ->rules('required', 'max:255', 'string')
                ->placeholder('Description'),

            Boolean::make('Active')
                ->rules('required', 'boolean')
                ->placeholder('Active'),

            Textarea::make('Pkcs')
                ->rules('required', 'max:255', 'string')
                ->placeholder('Pkcs'),

            Password::make('Password')
                ->rules('nullable')
                ->placeholder('Password')
                ->hideFromIndex()
                ->hideFromDetail(),

            Select::make('Type')
                ->rules('required', 'in:pfx,p12,pem')
                ->searchable()
                ->options([
                    'pfx' => 'Pfx',
                    'p12' => 'P12',
                    'pem' => 'Pem',
                ])
                ->displayUsingLabels()
                ->placeholder('Type'),

            Text::make('Url Prefix')
                ->rules('required', 'max:255', 'string')
                ->placeholder('Url Prefix'),

            Text::make('Success Url')
                ->rules('required', 'max:255', 'string')
                ->placeholder('Success Url'),

            Text::make('Error Url')
                ->rules('nullable', 'max:255', 'string')
                ->placeholder('Error Url'),

            Select::make('Environment')
                ->rules('required', 'in:test,prod')
                ->searchable()
                ->options([
                    'test' => 'Test',
                    'prod' => 'Prod',
                ])
                ->displayUsingLabels()
                ->placeholder('Environment')
                ->default('test'),

            Textarea::make('Layout')
                ->rules('required', 'max:255', 'json')
                ->placeholder('Layout'),

            Textarea::make('Languages')
                ->rules('required', 'max:255', 'json')
                ->placeholder('Languages'),

            Textarea::make('Extra Html')
                ->rules('required', 'max:255', 'string')
                ->placeholder('Extra Html'),

            HasMany::make('AllBankidRequests', 'allBankidRequests'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
