window.onload = function(){
	tip("tooltip");
}

function displayTip(e){
	if(e && e.stopPropagation){
		e.stopPropagation();
	} else {
		e = window.event;
		e.cancelBubble = true;
	}

	var elem = e.target;

	var tip = false;
	if(elem.getPropertyValue){
		tip = elem.getPropertyValue("title");
		if(!tip || tip == ""){
			tip = elem.getPropertyValue("tip");
		} else {
			elem.setPropertyValue("tip", tip);
			elem.removePropertyValue("title");
		}
	} else {
		tip = elem.getAttribute("title");
		if(!tip || tip == ""){
			tip = elem.getAttribute("tip");
		} else {
			elem.setAttribute("tip", tip);
			elem.setAttribute("title", "");
		}
	}

	hideTip();

	if(tip){
		var t = document.createElement("div");
		var arrow = document.createElement("div");
		arrow.setAttribute("class", "tip-arrow");
		var inner = document.createElement("div");
		inner.setAttribute("class", "tip-inner");
		inner.innerHTML = tip;

		t.className = "tip-tip ";

		t.appendChild(arrow);
		t.appendChild(inner);
		document.body.appendChild(t);

		var pos = offset(elem);

		var direction;
		if(pos.top > t.offsetHeight){
			direction = "bottom";
			pos.top -= t.offsetHeight + 5;
		} else {
			direction = "top";
			pos.top += elem.offsetHeight;
		}

		if(pos.left + t.offsetWidth > window.innerWidth){
			direction += " right";
			pos.left -= t.offsetWidth;
			pos.left += elem.offsetWidth;
		} else {
			direction += " left";
		}

		t.className += direction;

		t.style.top = pos.top + "px";
		t.style.left = pos.left + "px";
	}
}

function hideTip(){
	var tips = document.getElementsByClassName("tip-tip");
	for(var i = 0; i < tips.length; i++){
		document.body.removeChild(tips[i]);
	}
}

function tip(className){
	var tips = document.getElementsByClassName(className);

	for(var i = 0; i < tips.length; i++){
		tips[i].addEventListener("mouseover", displayTip, false);
		tips[i].addEventListener("mouseout", hideTip, false);
	}
}

function offset(elem){
	var xy = {"left": 0, "top": 0};
	if(elem){
		xy = {"left": elem.offsetLeft, "top": elem.offsetTop};
		var par = offset(elem.offsetParent);
		for(var key in par){
			xy[key] += par[key];
		}
	}

	return xy;
}
