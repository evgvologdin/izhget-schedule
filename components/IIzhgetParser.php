<?php

namespace app\components;

/**
 * Interface IIzhgetParser
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-22
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
interface IIzhgetParser
{
    const ERR_SERVER = 500;

    /**
     * Список остановок
     */
    public function getStations();
    
    /**
     * Название остановки
     * @param integer $id
     */
    public function getStationName($id);
    
    /**
     * Координаты остановки
     * @param integer $id
     */
    public function getStationCoords($id);
    
    /**
     * Остановки на которых возможны пересадки
     */
    public function getTransferStations();
    
    /**
     * Получение списка маршрутов
     * @param integer $from
     * @param integer $to
     * @param \DateTime $date
     */
    public function getRoutes($from, $to, \DateTime $date);
    
    /**
     * Получение списка маршрутов с пересадкой
     * @param integer $from
     * @param integer $to
     * @param integer $branch
     * @param \DateTime $date
     */
    public function getTransferRoutes($from, $to, $branch, \DateTime $date);
    
    /**
     * Название маршрута
     * @param integer $from
     * @param integer $to
     * @param integer $branch
     */
    public function getRouteName($from, $to, $branch = false);
}
