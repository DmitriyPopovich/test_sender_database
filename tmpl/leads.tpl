<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />


<div class="container" id="head_container">
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-12  form" role="form">
                            <div class="row search_panel_group" id="statistic">
                                <form  name="leads"  action="leads.html" method="get" >
                                    <div class="col col-sm-4 form-group">
                                        <br />
                                    </div>
                                    <div id="date-container" class="col col-sm-6 form-group">
                                        <div class="input-daterange input-group" id="datepicker">
                                            <span class="input-group-addon">Start</span>
                                           <input readonly type="text" class="input-sm form-control dp" value="<?=$search['start']?>" placeholder="<?=date('Y-m-d',time())?>" name="start" />
                                            <span class="input-group-addon">End</span>
                                            <input readonly type="text" class="input-sm form-control dp" value="<?=$search['end']?>"  placeholder="<?=date('Y-m-d',strtotime('+7 days'))?>" name="end" />
                                        </div>
                                    </div>

                                    <div class="form-group col col-sm-2">
                                        <button type="submit" class="form-control btn btn-success" ><i class="glyphicon glyphicon-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-xs-6">
                            <br />
                        </div>
                        <div class="col col-xs-3">
                            <br />
                        </div>
                        <div class="col col-xs-3">
                            <div class="btn-group pull-right">
                                <a href="<?=$url_links['link_update'] ?>"" class="btn btn-warning" name="link_3" >Обновить статусы</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" id="table_1">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-list">
                            <tbody>
                            <?php if(!count($data)){ ?>
                            <tr><td colspan="4">Лиды отсутствуют</td></tr>
                            <?php }  ?>
                            <?php if(count($data)){ ?>
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>email</th>
                                <th>status</th>
                                <th>ftd</th>
                            </thead>
                            <?php }  ?>

                            <?php foreach($data as $key=>$value){ ?>
                            <tr>
                                <td id="goalID_<?=$value['foreign_key']?>">
                                    <?=$value['foreign_key']?>
                                </td>
                                <td class="offer_<?=$value['id']?>">
                                    <?=$value['email'] ?>
                                </td>
                                <td class="code_<?=$value['id']?>">
                                    <?=$value['status_kc'] ?>
                                </td>
                                <td>
                                    <?=$value['ftd']?>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>