// $Id: dropdown.js 6862 2007-04-04 14:59:52Z vasyl $
/*
 * <pre>
 * Copyright (c) 2004-2006 by Zapatec, Inc.
 * http://www.zapatec.com
 * 1700 MLK Way, Berkeley, California,
 * 94709, U.S.A.
 * All rights reserved.
 * </pre>
 */

/**
*  This is simple class for creating drop-down lists.
* @param config [object] - pane config.
*
* Constructor recognizes the following properties of the config object
* \code
*	property name			| description
*------------------------------------------------------------------------------
*	element					| [string or object] Reference to DOM element.
*							|	Created list will be displayed below it.
*							|	Required.
*	hook					| [string or object] Element that invokes dropdown.
*							|	Optional.
*	onselect				| [function] Function that will be called when user
*							|	click on some row in dropdown. One argument
*							|	will be passed to function: array of value in
*							|	clicked row.
* \endcode
*/
Zapatec.DropDown = function(objArgs){
	if(arguments.length == 0){
		objArgs = {};
	}

	Zapatec.DropDown.SUPERconstructor.call(this, objArgs);
}

// Inherit SuperClass
Zapatec.inherit(Zapatec.DropDown, Zapatec.Widget);

Zapatec.DropDown.prototype.init = function(objArgs){
	this.config.element = null;
	this.config.hook = null;
	this.config.onselect = null;

	// processing Widget functionality
	Zapatec.DropDown.SUPERclass.init.call(this, objArgs);

	if(typeof(this.config.element) == 'string'){
		this.config.element = document.getElementById(this.config.element);
	}

	if(this.config.element == null){
		throw "No target element given";
	}

	// dropdown container
	this.container = null;

	// table that will contain list
	this.table = null;

	// table header
	this.header = null;

	// table body
	this.body = null;

	// two-dimensional array with values.
	this.content = {};

	var self = this;

	if(this.config.hook){
		// do not hide dropdown if user clicks on hood element
		Zapatec.Utils.addEvent(this.config.hook, 'click', function(){self.isVisible = true;});
	}

	// hide dropdown if ESC key was pressed
	Zapatec.Utils.addEvent(document, 'keypress', function(e) {
		if (!e){
			e = window.event;
		}

		if (e.keyCode == 27){
			self.hide();
		}
	});

	// hide dropdown if user clicks anywhere except dropdown or hook.
	Zapatec.Utils.addEvent(document, 'click', function(e) {
		if(!self.isVisible){
			self.hide();
		}

		self.isVisible = false;
	});
}

/**
* Returns reference to DropDown container element
* @return reference to DropDown container element
*/
Zapatec.DropDown.prototype.getContainer = function(){
	return this.container;
}

/**
* Set dropdown content to given array
*	@param objSource - [object] JSON object with structure like:
*	{
*		"header": [ // describes list header. Optional
*			{
*				name: "Col name1", // column name
*				style: "color: blue", // apply this style to current column header
*				colStyle: "color: blue" // apply this style to all cells in this col
*				colClassName: "customCol" // add this class to all cells in this col
*			},
*			...
*		],
*		"body": [ // describes list content. Required.
*			["str1, col1", "str1, col2"], // array with values
*			...
*		]
*	}
*/
Zapatec.DropDown.prototype.setContent = function(objSource){
	if(objSource == null){
		return null;
	}

	this.content = objSource;
}

/**
* Shows dropdown list.
*/
Zapatec.DropDown.prototype.show = function(){
	if(this.container != null){
		this.hide();
	}

	this.isVisible = false;

	// create dropdown container
	this.container = Zapatec.Utils.createElement("div");
	this.container.className = this.getClassName({prefix: "zpDropDown", suffix: "Container"})
	this.container.style.position = 'absolute';
	this.container.style.display = 'none';
	this.table = Zapatec.Utils.createElement("table");
	this.table.border = 0;
	this.table.cellSpacing = 0;
	this.container.appendChild(this.table);

	this.container.style.zIndex = Zapatec.Utils.getMaxZindex();
	var self = this;
	this.container.onclick = function(){self.isVisible = true;}

	this.header = this.table.appendChild(document.createElement("thead"));
	this.body = this.table.appendChild(document.createElement("tbody"));

	document.body.appendChild(this.container);

	// fill dropdown list with data
	this.fillRows();

	// position container to config.element
	var pos = Zapatec.Utils.getElementOffset(this.config.element);
	this.container.style.left = pos.x