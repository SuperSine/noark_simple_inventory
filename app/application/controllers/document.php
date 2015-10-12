<?php 

class Document_Controller extends Base_Controller {

    // Buttons above the main content
    public $item_buttons = null;

    // Buttons above the main content
    public $submenu = null;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->submenu = Navigation::submenu('inventory');

        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->add_item_button(array(
                                    'icon' => 'icon-plus-sign',
                                    'link' => 'document@add',
                                    'text' => __('site.add_document')))
                                ->get_item_buttons();
    }


    /**
     * Category index
     * @return redirect Redirecting to document list
     */
    public function get_index()
    {
        return Redirect::to_action('document@list');
    }


    /**
     * Get document list
     * @return view document list
     */
    public function get_list()
    {
        if( ! Auth::can('view_documents'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }

        return View::make('layout.index')
                ->nest('header', 'layout.blocks.header', array(
                    'submenu' => $this->submenu,
                ))
                ->nest('main', 'document.list', array(
                    'list' => $this->fetch_documents(),
                    'status' => $this->status,
                    'item_buttons' =>  $this->item_buttons
                ));
    }


    /**
     * Add document page
     * @return Response
     */
    public function get_add()
    {
        if( ! Auth::can('add_documents'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('documents@list');
        }

        return View::make('layout.index')
                        ->nest('header', 'layout.blocks.header', array(
                            'submenu' => $this->submenu,
                        ))
                        ->nest('main', 'document.add', array(
                            'status' => $this->status,
                            'item_buttons' =>  $this->item_buttons
                    ));
    }


    /**
     * Add document Form submission
     * @return Response
     */
    public function post_add()
    {
        if( ! Auth::can('add_documents'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('documents@list');
        }

        if(Input::get('submit'))
        {
            $rules = array(
                'name'          => 'required|max:200',
            );

            $input = Input::all();

            $validation = Validator::make($input, $rules);

            if($validation->fails())
            {
                Vsession::cadd('r',  $validation->errors->first())->cflash('status');
            }
            else
            {
                $items['name']          = Input::get('name');
                $items['description']   = Input::get('description');
                $items['po_num']        = Input::get('po_num');
                $items['pd_date']       = Input::get('pd_date');

                foreach ($items as $key => $value)
                {
                    $items[$key] = ($value !== '') ? trim(filter_var($value, FILTER_SANITIZE_STRING)) : null;
                }


                try
                {
                    $date = new \DateTime;
                    $items['pd_date'] = new \DateTime($items['pd_date']);

                    $id = DB::table('documents')->insert_get_id(array(
                                            'name'          => $items['name'],
                                            'description'   => $items['description'],
                                            'po_num'        => $items['po_num'],
                                            'pd_date'       => $items['pd_date'],
                                            'created_at'    => $date,
                                            'updated_at'    => $date
                    ));
                }
                catch(Exception $e)
                {
                    Vsession::cadd('r', /*__('site.st_document_not_saved')*/$e)->cflash('status');
                    return Redirect::to_action('document@add');
                }

                Vsession::cadd('g', __('site.st_document_saved'))->cflash('status');
                return Redirect::to_action('document@add');
            }
        }

        return View::make('layout.index')
                        ->nest('header', 'layout.blocks.header', array(
                            'submenu' => $this->submenu,
                        ))
                        ->nest('main', 'document.add', array(
                            'status' => $this->status,
                            'item_buttons' =>  $this->item_buttons 
                        ));
    }


    /**
     * Add document page
     * @return Response
     */
    public function get_edit($id = null)
    {
        if( ! Auth::can('edit_documents'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('document@list');
        }

        if($id !== null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        }
        else
        {
            Redirect::to_action('document@list');
        }

        if(null === $document = $this->fetch_document($id))
        {
            return Redirect::to_action('document@list');
        }
//var_dump($document);exit();
        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->reset_item_buttons()
                                ->add_item_button(array(
                                    'icon'  => 'icon-minus-sign icon-white',
                                    'link'  => 'document@delete/'.$id,
                                    'text'  => __('site.delete_document'),
                                    'class' => 'btn-danger delete',
                                ))
                                ->get_item_buttons();

        return View::make('layout.index')
                        ->nest('header', 'layout.blocks.header', array(
                            'submenu' => $this->submenu,
                        ))
                        ->nest('main', 'document.edit', array(
                            'document' => $document,
                            'status' => $this->status,
                            'item_buttons' =>  $this->item_buttons
                    ));
    }

    
    /**
     * Edit document Form submission
     * @return Response
     */
    public function post_edit($id = null)
    {

        if( ! Auth::can('edit_documents'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('document@list');
        }

        if(Input::get('submit'))
        {
            // ID
            if($id !== null)
            {
                $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
            }
            else
            {
                Redirect::to_action('document@list');
            }

            $rules = array(
                'name'          => 'required|max:200',
            );

            $input = Input::all();

            $validation = Validator::make($input, $rules);

            if($validation->fails())
            {
                Vsession::cadd('r',  $validation->errors->first())->cflash('status');
            }
            else
            {
                $items['name']          = Input::get('name');
                $items['description']   = Input::get('description');
                $items['po_num']        = Input::get('po_num');
                $items['pd_date']       = Input::get('pd_date');

                foreach ($items as $key => $value)
                {
                    $items[$key] = ($value !== '') ? trim(filter_var($value, FILTER_SANITIZE_STRING)) : null;
                }

                try
                {
                    $date = new \DateTime;

                    DB::table('documents')
                        ->where_id($id)
                        ->update(array(
                            'name'          => $items['name'],
                            'description'   => $items['description'],
                            'po_num'        => $items['po_num'],
                            'pd_date'       => $items['pd_date'],                            
                            'updated_at'    => $date
                    ));
                }
                catch(Exception $e)
                {
                    Vsession::cadd('r', __('site.st_document_not_saved'))->cflash('status');
                    return Redirect::to_action('document@add');
                }

                Vsession::cadd('g', __('site.st_document_edited'))->cflash('status');
                return Redirect::to_action('document@edit/' . $id);
            }
        }

        return $this->get_edit($id);
    }


    public function get_delete($id = null)
    {
        if( ! Auth::can('delete_documents'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('document@list');
        }

        // ID
        if($id !== null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        }
        else
        {
            Redirect::to_action('document@list');
        }

        if($delete = DB::table('documents')->delete($id))
        {
            Vsession::cadd('g', __('site.st_document_deleted'))->cflash('status');
        }
        else
        {
            Vsession::cadd('g', __('site.st_document_not_deleted'))->cflash('status');
        }

        return Redirect::to_action('document@list');
    }


    /*
    * Private helper functions
    */


    /**
     * Getting document list from DB
     * @return array Category names with ID as key
     */
    private function fetch_documents()
    {
        $catlist = array();

        $documents = DB::table('documents')
                            ->get(array(
                                'documents.id',
                                'documents.name AS name',
                                'documents.description',
                                'documents.created_at'
                            ));

        return $documents;
    }

    /**
     * Get document data by ID
     * @param  int $id document ID
     * @return Response
     */
    private function fetch_document($id)
    {
        $document = DB::table('documents')->where_id($id)->first();

        return $document;
    }


}


