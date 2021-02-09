<?php

use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;

class CreateRoleStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = $this->createModule();
        $this->createTabsBlocksFields($module);
        $this->createFilters($module);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Module::where('name', 'role')->forceDelete();
    }

    protected function createModule()
    {
        $module = new Module();
        $module->name = 'role';
        $module->icon = 'lock';
        $module->model_class = 'Uccello\Core\Models\Role';
        $module->data = [ "package" => "uccello/uccello", "admin" => true, "mandatory" => true, "menu" => "uccello.tree" ];
        $module->save();

        return $module;
    }

    protected function createTabsBlocksFields($module)
    {
        // Main tab
        $tab = new Tab();
        $tab->label = 'tab.main';
        $tab->icon = null;
        $tab->sequence = $module->tabs()->count();
        $tab->module_id = $module->id;
        $tab->save();

        // General block
        $block = new Block();
        $block->label = 'block.general';
        $block->icon = 'info';
        $block->sequence = $module->blocks()->count();
        $block->tab_id = $tab->id;
        $block->module_id = $module->id;
        $block->save();

        // Name
        $field = new Field();
        $field->name = 'name';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = [ 'rules' => 'required' ];
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Parent
        $field = new Field();
        $field->name = 'parent';
        $field->uitype_id = uitype('entity')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = [ 'module' => 'role', 'field' => 'name' ];
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        $field = new Field();
        $field->name = 'see_descendants_records';
        $field->uitype_id = uitype('boolean')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = [ 'info' => 'field_info.see_descendants_records'];
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Description
        $field = new Field();
        $field->name = 'description';
        $field->uitype_id = uitype('textarea')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = null;
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // System block
        $block = new Block();
        $block->label = 'block.system';
        $block->icon = 'settings';
        $block->data = null;
        $block->sequence = $module->blocks()->count();
        $block->tab_id = $tab->id;
        $block->module_id = $module->id;
        $block->save();

        // Created at
        $field = new Field();
        $field->name = 'created_at';
        $field->uitype_id = uitype('datetime')->id;
        $field->displaytype_id = displaytype('detail')->id;
        $field->data = null;
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Updated at
        $field = new Field();
        $field->name = 'updated_at';
        $field->uitype_id = uitype('datetime')->id;
        $field->displaytype_id = displaytype('detail')->id;
        $field->data = null;
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Domain
        $field = new Field();
        $field->name = 'domain';
        $field->uitype_id = uitype('entity')->id;
        $field->displaytype_id = displaytype('detail')->id;
        $field->data = ['module' => 'domain'];
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();
    }

    protected function createFilters($module)
    {
        $filter = new Filter();
        $filter->module_id = $module->id;
        $filter->domain_id = null;
        $filter->user_id = null;
        $filter->name = 'filter.all';
        $filter->type = 'list';
        $filter->columns = [ 'name', 'parent', 'description', 'see_descendants_records'  ];
        $filter->conditions = null;
        $filter->order = null;
        $filter->is_default = true;
        $filter->is_public = false;
        $filter->data = [ 'readonly' => true ];
        $filter->save();
    }
}
