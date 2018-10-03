<?php

namespace Pyrite\XWING;


class Constants {
	public static $FG_AI = array('Rookie', 'Novice', 'Veteran', 'Officer', 'Ace', 'Top Ace');
    public static $FG_SHIPTYPES = array(
        'None','X-Wing','Y-Wing','A-Wing','TIE Fighter','TIE Interceptor','TIE Bomber','Gunboat','Transport','Shuttle',
        'Tug','Container','Freighter','Calamari Cruiser','Nebulon B Frigate','Corellian Corvette','Imperial Star Destroyer',
        'TIE Advanced','B-Wing'
    );
    public static $FG_STARSHIPS = array('Calamari Cruiser','Nebulon B Frigate','Corellian Corvette','Imperial Star Destroyer');
    public static $FG_POINTS = array(
        0, 600, 400, 800, 400, 600, 600, 800, 600, 800,
        200, 800, 1200, 6000, 4000, 1600, 8000,
        1800, 1800 //only guessing re: BWing
    );

    public static $FG_OBJECTIVES = array(
        'None', 'all destroyed', 'all survive', 'all captured', 'all docked', 'Special craft destroyed', 'Special craft survive',
        'Special craft captured', 'Special craft docked', '50% destroyed', '50% survive', '50% captured', '50% docked',
        'all identified', 'Special craft identifed', '50% identified', 'Arrive'
    );
}