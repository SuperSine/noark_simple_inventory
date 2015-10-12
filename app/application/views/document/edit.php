    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo $document->name; ?></h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <div class="add_contact add">
                <?php echo Form::open_for_files(); ?>
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                    <div class="row1">
                        <?php
                        echo Form::label('name', __('site.name'));
                        echo Form::text('name', (Input::get('name') != '') ? Input::get('name') : $document->name, array('class' => 'wide'));
                        ?>
                    </div>
                    <div class="row1">
                        <?php
                        echo Form::label('po_num', __('site.po_num'));
                        echo Form::text('po_num', (Input::get('po_num') != '') ? Input::get('po_num') : $document->po_num, array('class' => 'wide'));
                        ?>
                    </div>
                    <div class="row1">
                        <?php
                        echo Form::label('pd_date', __('site.pd_date'));
                        echo Form::date('pd_date', (Input::get('pd_date') != '') ? Input::get('pd_date') : $document->pd_date, array('class' => 'wide'));
                        ?>
                    </div>                    
                    <div class="row1">
                        <?php
                        echo Form::label('description', __('site.description'));
                        echo Form::textarea('description', (Input::get('description') != '') ? Input::get('description') : $document->description, array('class' => 'wide'));
                        ?>
                    </div>
                <div class="row1 submit">
                    <?php echo Form::submit(__('site.edit_document'), array('name'=>'submit', 'class' => 'btn btn-primary')); ?>
                </div>

                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>