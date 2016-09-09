<?php
/**
 * @var $this \yii\web\View
 * @var #model \app\models\Shedule
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
?>
<section id="content" class="<?=($routes == false ) ? 'view-min-mode' : 'view-max-mode';?>">
    <div class="col-left">
        <?php $form = ActiveForm::begin([
            'enableClientScript'     => false,
            'enableClientValidation' => false,
            'options'                => [
                'id'    => 'get-routes',
                'class' => 'form',
            ]
        ]);?>
        <div class="block">
            <h2>Выбор маршрута</h2>
            <?php
                echo $form->field($model, 'from')
                          ->dropDownList([], [
                                'id'         => 'from-station', 
                                'prompt'     => 'Выберите остановку',
                                'data-value' => $model->from
                            ]);
            ?>

            <?php
                echo $form->field($model, 'to')
                          ->dropDownList([], [
                                'id'         => 'to-station', 
                                'prompt'     => 'Выберите остановку',
                                'data-value' => $model->to
                            ]);
            ?>

            <?php
                echo $form->field($model, 'has_date')
                          ->checkbox(['id' => 'has-date']);
            ?>

            <div id="select-time-block">
                <?php
                    echo $form->field($model, 'selected_time')
                              ->textInput(['id' => 'select-time']);
                ?>
            </div>

            <?php
                echo $form->field($model, 'current_time')
                          ->hiddenInput([
                              'id'    => 'current-time',
                              'value' => (empty($model->current_time)) ? date('d.m.Y H:i') : $model->current_time
                          ])
                          ->label(false)
                          ->error(false);
            ?>

            <?php
                echo $form->field($model, 'transfer_stations')
                          ->checkboxList(ArrayHelper::map($model->transferStations, 'id', 'name'), [
                                'id' => 'transfer-stations'
                            ])
            ?>
            <div class="form-group text-center">
                <div 
                    class="yashare-auto-init" 
                    data-yashareL10n="ru" 
                    data-yashareType="small" 
                    data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus,pinterest" 
                    data-yashareTheme="counter" 
                    data-yashareImage="/client/img/screen.png"
                ></div>
            </div>
        </div>
        <button class="submit" id="submit-button">Показать</button>
        <?php ActiveForm::end();?>
    </div>
    <div class="col-right" id="routes-list">
    <?php if($routes !== false):?>
        <?php foreach($routes AS $route):?>
            <?php if($route['routes'] === false):?>
            <article class="block error">
                <h2><?=Html::encode($route['name']);?></h2><i class="close"></i>
                <div class="message">Ошибка. Нет рейсов на выбранное время</div>
            </article>
            <?php else:?>
            <article class="block">
                <h2><?=Html::encode($route['name']);?></h2><i class="close"></i>
                <?php foreach($route['routes'] AS $info):?>
                <div class="route">
                    <div class="info">
                        <span class="col start"><?=$info['number']?></span>
                        <span class="col time">
                            <small class="start"><?=$info['from_time']?></small>
                            <small class="finish"><?=$info['to_time']?></small>
                        </span>
                        <?php if(isset($info['transfer'])):?>
                        <span class="col branch"><?=$info['transfer']['route']['number']?></span>
                        <span class="col time">
                            <small class="start"><?=$info['transfer']['route']['from_time']?></small>
                            <small class="finish"><?=$info['transfer']['route']['to_time']?></small>
                        </span>
                        <?php endif?>
                        <span class="col finish">&nbsp;</span>
                    </div>
                    <div class="timeline">
                        <small><?=$info['summary_time'];?></small><hr />
                    </div>
                </div>
                <?php endforeach;?>
            </article>
            <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>        
    </div>
</section>