function __getDefinedValue(sth, defaultV){
		if(typeof(sth) == 'undefined'){
			return defaultV;
		}
		return sth;
};

var ym_game_hero = function(herofeature){
	var H = this;

	H.feature = {
		lvl:1, //青铜,白银,黄金,钻石,王者 1-5
		luckpoint:0
		ti:0,
		su:0,
		gong:0,
		fang:0,
		qinhe:50,
		ji:0
	};

	H.getSpecialGenRate = function(){
		return H.feature.lvl * 0.1;
	};

};

var ym_game_user = function(username, feature){
	var U = this;

	U.username = username;
	U.feature = {
		tong:5, wu:5, zhi:5, mei:5, lvl:1 
	};

	U.blood = 300;

	U.heros = [];

	if(typeof(feature)=='undefined'){
		//统，武，智，魅
		U.feature.tong = __getDefinedValue(feature.tong, U.feature.tong);
		U.feature.wu = __getDefinedValue(feature.wu, U.feature.wu);
		U.feature.zhi = __getDefinedValue(feature.zhi, U.feature.zhi);
		U.feature.mei = __getDefinedValue(feature.mei, U.feature.mei);
		U.feature.lvl = __getDefinedValue(feature.lvl, U.feature.lvl);
	}

	U.addHero = function(hero){
		U.heros[U.heros.length] = hero;
	};



};

var ym_game_logic = function(){
	var G13 = this;
	G13.userInitPoints = [[120,8],
						[122,8],
						[124,8],
						[126,8],
						[128,9],
						[130,9],
						[132,9],
						[134,9],
						[136,9],
						[138,10],
						[140,10],
						[142,10],
						[144,10],
						[146,10],
						[148,12],
						[150,12],
						[152,12],
						[154,12],
						[156,12],
						[158,14],
						[160,14],
						[162,14],
						[164,14],
						[166,14],
						[168,16],
						[170,17],
						[172,18],
						[174,19],
						[176,20],
						[180,21]];
	G13.protectedValues = [
						10/100,
						15/100,
						20/100,
						22/100,
						25/100,
						27/100,
						29/100,
						31/100,
						34/100,
						36/100,
						38/100,
						40/100,
						42/100,
						44/100,
						46/100,
						48/100,
						50/100,
						52/100,
						54/100,
						56/100,
						58/100,
						60/100,
						62/100,
						64/100,
						66/100,
						68/100,
						70/100,
						72/100,
						74/100,
						75/100,
						76/100,
						77/100,
						78/100,
						79/100,
						80/100
	];

	G13.jiMissRates = [
							10/100,
							15/100,
							20/100,
							25/100,
							30/100,
							35/100,
							40/100,
							45/100,
							50/100
	];

	G13.missRates = [
							10/100,
							15/100,
							20/100,
							25/100,
							30/100,
							35/100,
							40/100,
							45/100,
							50/100
	];

	G13.baoRates = [
							10/100,
							15/100,
							20/100,
							28/100,
							36/100,
							44/100,
							52/100,
							62/100,
							75/100
	];

	G13.lianRates = [
							10/100,
							15/100,
							20/100,
							28/100,
							36/100,
							44/100,
							52/100,
							62/100,
							75/100
	];

	G13.checkHitWithRate = function(rate){
		return (Math.random() * 100) <= (rate * 100);
	};

	G13.getLianRate = function(sudiff){
		if(sudiff <=0)
			return 0;
		if(sudiff >= G13.lianRates.length)
			sudiff = G13.lianRates.length;

		return G13.lianRates[sudiff - 1]; 
	};

	G13.getBaoRate = function(attachDiff){
		if(attachDiff <=0)
			return 0;
		if(attachDiff >= G13.baoRates.length)
			attachDiff = G13.baoRates.length;

		return G13.baoRates[attachDiff - 1]; 
	};

	//sudiff = attach - protected
	G13.getMissRate = function(sudiff){
		if(sudiff >= 0)
			return 0;
		sudiff = -1 * sudiff;
		if(sudiff >= G13.missRates.length)
			sudiff = G13.missRates.length;

		return G13.missRates[sudiff - 1]; 
	}; 

	G13.getJiMissRate = function(sudiff){
		if(sudiff >= 0)
			return 0;
		sudiff = -1 * sudiff;
		if(sudiff >= G13.jiMissRates.length)
			sudiff = G13.jiMissRates.length;

		return G13.jiMissRates[sudiff - 1]; 
	};

	G13.getProtectedPoint = function(fang){
		if(fang >= G13.protectedValues.length){
			fang = G13.protectedValues.length;
		}
		return 1 - G13.protectedValues[fang-1];
	};

	G13.getUserInitPoint = function(lv){
		return G13.userInitPoints[lv - 1];
	};
};

var YM_GAME_LOGIC = false;

$(function(){
	YM_GAME_LOGIC = new ym_game_logic();
});

