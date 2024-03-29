    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.check_in_item'); ?></h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <div class="add_item add">
                <?php if(isset($item->id)): ?>
                <?php echo View::make('layout.blocks.nav_sections')->with('id', $item->id); ?>
                <?php endif; ?>
                <?php echo Form::open_for_files(); ?>
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                <div class="left">
                    <div class="row1">
                        <?php

                        $fields = array(
                            'name',
                            'buying_price',
                            'code'
                        );

                        $values = array();

                        foreach ($fields as $field)
                        {
                            if(Input::get($field) != '')
                            {
                                $values[$field] = Input::get($field);
                            }
                            elseif(isset($item->$field))
                            {
                                $values[$field] = $item->$field;
                            }
                            else
                            {
                                 $values[$field] = '';
                            }
                        }

                        ?>

                        <?php
                        echo Form::label('name', __('site.name'));
                        echo Form::text('name', $values['name'], array('class' => 'half', 'id' => 'name', 'disabled' => 'disabled'));
                        ?>
                    </div>
                    <div class="row1">
                        <div class="col1">
                        <?php
                        echo Form::label('quantity', __('site.quantity'));
                        echo Form::text('quantity', (Input::get('quantity') != '') ? Input::get('quantity') : '1');
                        ?>
                        </div>                     
                        <!--
                        <div class="col2">
                        <?php
                        echo Form::label('buying_price', __('site.buying_price'));
                        echo Form::text('buying_price', $values['buying_price'], array('id' => 'buying_price'));
                        ?>
                        </div>
                        -->
                    </div>
                    <div class="row1">
                        <div class="col1">
                        <?php
                        echo Form::label('document', __('site.document'));
                        echo Form::text('document', (Input::get('document') != '') ? Input::get('document') : '');
                        ?>
                        </div>   
                        <div class="col1">
                        <?php
                        echo Form::label('po_num', __('site.po_num'));
                        echo Form::text('po_num', (Input::get('po_num') != '') ? Input::get('po_num') : '');
                        ?>
                        </div>         
                        <div class="col1">
                        <?php
                        echo Form::label('pd_date', __('site.pd_date'));
                        echo Form::date('pd_date', (Input::get('pd_date') != '') ? Input::get('pd_date') : '');
                        ?>
                        </div>                                           
                    </div>
                </div>
                <div class="right">
                    <div class="row1">
                        <?php 
                        echo Form::label('code', __('site.code'));
                        echo Form::text('code', $values['code'], array('class' => 'half', 'id' => 'code'));
                        ?>
                    </div>
                    <div class="row1">
                        <div class="col1">
                        <?php
                        echo Form::label('doc_number', __('site.doc_number'));
                        echo Form::text('doc_number', Input::get('doc_number'), array('id' => 'doc_number'));
                        ?>
                        </div>
                        <div class="col2">
                        <?php
                        echo Form::label('date', __('site.date'));
                        echo Form::text('date', Input::get('date'), array('id' => 'datepicker'));
                        ?>
                        </div>
                    </div>
                </div>
                <div class="row1">
                        <?php
                        echo Form::label('note', __('site.note'));
                        echo Form::textarea('note', Input::get('note'), array('class' => 'wide', 'rows' => 5));
                        ?>
                    </div>
                <div class="row1 submit">
                    <?php echo Form::submit(__('site.check_in_item'), array('name'=>'submit', 'class' => 'btn btn-primary')); ?>
                </div>

                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>
    <script>
    $(function() {

        var datepicker = $("#datepicker");
        datepicker.datepicker();
        datepicker.datepicker("setDate", new Date());
        datepicker.datepicker("option", "dateFormat", "yy-mm-dd");
        datepicker.datepicker($.datepicker.regional[ "<?php echo Config::get('application.language') ?>" ]);
        
        $("#autocontact").autocomplete({
            source: "<?php echo action('ajax@contacts'); ?>",
            minLength: 1
        });

        $("#code").on("keyup change paste",function(e){
            setTimeout(function(){
                var val = $("#code").val();

                $.getJSON('<?php echo action('ajax@checkin_fields'); ?>',{code : val} , function(data) {
                    $("#name").val(data.name);
                    $("#buying_price").val(data.buying_price);
                });

            },0);
        });

    });
    </script>