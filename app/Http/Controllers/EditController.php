<?php

namespace Sardoj\Uccello\Http\Controllers;

use Debugbar;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;
use Illuminate\Support\Facades\Cache;
use Kris\LaravelFormBuilder\FormBuilder;
use Sardoj\Uccello\Forms\EditForm;
use Sardoj\Uccello\Tab;
use PHPUnit\Framework\MockObject\BadMethodCallException;
use Illuminate\Http\Request;


class EditController extends Controller
{
    protected $viewName = 'uccello::edit.main';
    protected $formBuilder;

    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        // Retrieve record or get a new empty instance
        $record = $this->getRecordFromRequest($request);

        // Get form
        $form = $this->getForm($record);

        return view($this->viewName, [
            'structure' => $this->getModuleStructure(),
            'form' => $form
        ]);
    }

    /**
     * Create or update record into database
     *
     * @param Domain $domain
     * @param Module $module
     * @return void
     */
    public function store(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module);        

        // Get entity class used by the module
        $entityClass = $this->module->entity_class;
        
        try
        {
            // Retrieve record or get a new empty instance
            $record = $this->getRecordFromRequest($request);

            // Get form
            $form = $this->getForm($record);

            // Redirect if form not valid (the record is made here)
            $form->redirectIfNotValid();

            // Save record
            $form->getModel()->save();

            // Redirect to detail view
            return redirect()->route('detail', ['domain' => $domain->slug, 'module' => $module->name, 'id' => $record->id]);
        }
        catch (\Exception $e) {}
            
        // If there was an error, redirect to edit page 
        // TODO: improve
        return redirect()->route('edit', ['domain' => $domain->slug, 'module' => $module->name, 'id' => $record->id, 'error' => 1]);
    }

    public function getForm($record = null)
    {
        return $this->formBuilder->create(EditForm::class, [
            'model' => $record,
            'data' => [
                'domain' => $this->domain,
                'module' => $this->module
            ]
        ]);
    }

    /**
     * Get module structure : tabs > blocks > fields
     * @return Module
     */
    protected function getModuleStructure()
    {
        return Module::find($this->module->id);
    }
}
