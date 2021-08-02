<?php

namespace Modules\Admin\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Support\Search\Searchable;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Ui\Facades\TabManager;
use Modules\Product\Entities\AdvancedOptions;
use Modules\Product\Entities\UserEvents;
use DB;
use Auth;
trait HasCrudActions
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->has('query')) {
            return $this->getModel()
                ->search($request->get('query'))
                ->query()
                ->limit($request->get('limit', 10))
                ->get();
        }

        if ($request->has('table')) {
            return $this->getModel()->table($request);
        }

        $events = validate_events();
        $data['events'] = DB::table('products')
                        ->join('product_translations','products.id','=','product_translations.product_id')
                        ->whereIn('products.id', $events)->select('product_translations.name','products.id')
                        ->orderBy('products.id','desc')->get()->toArray();

        return view("{$this->viewPath}.index",$data);
        // return view("{$this->viewPath}.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array_merge([
            'tabs' => TabManager::get($this->getModel()->getTable()),
            $this->getResourceName() => $this->getModel(),
        ], $this->getFormData('create'));

        return view("{$this->viewPath}.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $temp_data = DB::table('temp_data')->select(DB::raw('*'))->where('user_id', '=', Auth::user()->id)->get()->first();

        $this->disableSearchSyncing();

        $entity = $this->getModel()->create(
            $this->getRequest('store')->all()
        );

        if(!empty($this->getRequest('store')->advanced_label)) :
            $labels = $this->getRequest('store')->advanced_label;

            $prices = $this->getRequest('store')->advanced_price;
            $advanced_color = $this->getRequest('store')->advanced_color;
            $quantities = $this->getRequest('store')->advanced_quantity;

            $fees = $this->getRequest('store')->fee;



            $purchase_limit = $this->getRequest('store')->purchase_limit;


            foreach($labels as $key => $label) {
                AdvancedOptions::create([
                    'product_id' => $entity['id'],
                    'label' => $label,
                    'price' => $prices[$key],
                    'advanced_color' => $advanced_color[$key],
                    'quantity' => $quantities[$key],
                    'fee' => $fees[$key],
                    'purchase_limit' => $purchase_limit[$key],
                ]);
            }
        endif;
        //setting arrangement save tmp st
        if($entity){
            if ($temp_data!=null){
            $sitar_up = DB::table('products')->where('id', '=', $entity['id'] )->update( array( 'sitting_arrangement' => $temp_data->sitting_arrangement ) );
            if($sitar_up){
                $temp_data_del = DB::table('temp_data')->where('user_id', '=', Auth::user()->id)->delete();
                if($temp_data_del){

                }
            }
            }
        }
        //setting arrangement save tmp end
        $this->searchable($entity);

        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo($entity);
        }

        return redirect()->route("{$this->getRoutePrefix()}.index")
            ->withSuccess(trans('admin::messages.resource_saved', ['resource' => $this->getLabel()]));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entity = $this->getEntity($id);

        if (request()->wantsJson()) {
            return $entity;
        }

        return view("{$this->viewPath}.show")->with($this->getResourceName(), $entity);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data = array_merge([
            'tabs' => TabManager::get($this->getModel()->getTable()),
            $this->getResourceName() => $this->getEntity($id),
        ], $this->getFormData('edit', $id));
    //   echo "<pre>";
    //   print_r($data);
    //   die();
        return view("{$this->viewPath}.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $entity = $this->getEntity($id);

        $updateable = $this->getRequest('update')->all();
        

        if(isset($updateable['start_event']) && $updateable['start_event'] !=''){
            $updateable['start_event']= date('Y-m-d H:i:s', strtotime($updateable['start_event']));
        }    
        if(isset($updateable['end_event']) && $updateable['end_event'] !=''){
            $updateable['end_event']= date('Y-m-d H:i:s', strtotime($updateable['end_event']));
        }

// echo "<pre>"; print_r($updateable); exit('here');

        $this->disableSearchSyncing();
        if(!empty($updateable['assign_user'])  ){
            $assign_users= $updateable['assign_user'];
        }

        // $entity->update(
        //     $this->getRequest('update')->all()
        // );

        $entity->update(
            $updateable
        );
        // user event start
        if(!empty($assign_users)  ){
            $felt =UserEvents::where('product_id',$id )->get();
            if($felt){
                $del = UserEvents::where('product_id',$id )->delete();    
            }
            
            foreach ($assign_users as $key => $assign_user) {
                $updateUserEvent =array(
                    'user_id'=> $assign_user ,
                    'product_id'=> $id
                );  
                $feltt =UserEvents::create($updateUserEvent);
            }
        }
        // user event

        $this->searchable($entity);

        if (method_exists($this, 'redirectTo')) {

            return $this->redirectTo($entity)
                ->withSuccess(trans('admin::messages.resource_saved', ['resource' => $this->getLabel()]));
        }

        return redirect()->route("{$this->getRoutePrefix()}.index")
            ->withSuccess(trans('admin::messages.resource_saved', ['resource' => $this->getLabel()]));
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        $this->getModel()
            ->withoutGlobalScope('active')
            ->whereIn('id', explode(',', $ids))
            ->delete();
    }

    /**
     * Get an entity by the given id.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getEntity($id)
    {
        return $this->getModel()
            ->with($this->relations())
            ->withoutGlobalScope('active')
            ->findOrFail($id);
    }

    /**
     * Get the relations that should be eager loaded.
     *
     * @return array
     */
    private function relations()
    {
        return collect($this->with ?? [])->mapWithKeys(function ($relation) {
            return [$relation => function ($query) {
                return $query->withoutGlobalScope('active');
            }];
        })->all();
    }

    /**
     * Get form data for the given action.
     *
     * @param string $action
     * @param mixed ...$args
     * @return array
     */
    protected function getFormData($action, ...$args)
    {
        if (method_exists($this, 'formData')) {
            return  $this->formData(...$args);
        }

        if ($action === 'create' && method_exists($this, 'createFormData')) {
            return $this->createFormData();
        }

        if ($action === 'edit' && method_exists($this, 'editFormData')) {
            return $this->editFormData(...$args);
        }

        return [];
    }

    /**
     * Get name of the resource.
     *
     * @return string
     */
    protected function getResourceName()
    {
        if (isset($this->resourceName)) {
            return $this->resourceName;
        }

        return lcfirst(class_basename($this->model));
    }

    /**
     * Get label of the resource.
     *
     * @return void
     */
    protected function getLabel()
    {
        return trans($this->label);
    }

    /**
     * Get route prefix of the resource.
     *
     * @return string
     */
    protected function getRoutePrefix()
    {
        if (isset($this->routePrefix)) {
            return $this->routePrefix;
        }

        return "admin.{$this->getModel()->getTable()}";
    }

    /**
     * Get a new instance of the model.
     *
     * @return void
     */
    protected function getModel()
    {
        return new $this->model;
    }

    /**
     * Get request object
     *
     * @param string $action
     * @return \Illuminate\Http\Request
     */
    protected function getRequest($action)
    {
        if (! isset($this->validation)) {
            return request();
        }

        if (isset($this->validation[$action])) {
            return resolve($this->validation[$action]);
        }

        return resolve($this->validation);
    }

    /**
     * Disable search syncing for the entity.
     *
     * @return void
     */
    protected function disableSearchSyncing()
    {
        if ($this->isSearchable()) {
            $this->getModel()->disableSearchSyncing();
        }
    }

    /**
     * Determine if the entity is searchable.
     *
     * @return bool
     */
    protected function isSearchable()
    {
        return in_array(Searchable::class, class_uses_recursive($this->getModel()));
    }

    /**
     * Make the given model instance searchable.
     *
     * @return void
     */
    protected function searchable($entity)
    {
        if ($this->isSearchable($entity)) {
            $entity->searchable();
        }
    }
}
