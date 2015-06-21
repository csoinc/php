//** Tab Content script v2.0- © Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
//** Updated Oct 7th, 07 to version 2.0. Contains numerous improvements:
//   -Added Auto Mode: Script auto rotates the tabs based on an interval, until a tab is explicitly selected
//   -Ability to expand/contract arbitrary DIVs on the page as the tabbed content is expanded/ contracted
//   -Ability to dynamically select a tab either based on its position within its peers, or its ID attribute (give the target tab one 1st)
//   -Ability to set where the CSS classname "selected" get assigned- either to the target tab's link ("A"), or its parent container
//** Updated Feb 18th, 08 to version 2.1: Adds a "tabinstance.cycleit(dir)" method to cycle forward or backward between tabs dynamically
//** Updated April 8th, 08 to version 2.2: Adds support for expanding a tab using a URL parameter (ie: http://mysite.com/tabcontent.htm?tabinterfaceid=0) 

////NO NEED TO EDIT BELOW////////////////////////

function ddtabcontent(tabinterfaceid, relatedTabId){
	this.tabinterfaceid=tabinterfaceid //ID of Tab Menu main container
	this.tabs=document.getElementById(tabinterfaceid).getElementsByTagName("a") //Get all tab links within container
	this.enabletabpersistence=true
	this.hottabspositions=[] //Array to store position of tabs that have a "rel" attr defined, relative to all tab links, within container
	this.currentTabIndex=0 //Index of currently selected hot tab (tab with sub content) within hottabspositions[] array
	this.subcontentids=[] //Array to store ids of the sub contents ("rel" attr values)
	this.revcontentids=[] //Array to store ids of arbitrary contents to expand/contact as well ("rev" attr values)
	this.selectedClassTarget="link" //keyword to indicate which target element to assign "selected" CSS class ("linkparent" or "link")
	this.relatedtabid=relatedTabId //combine this tab with the relatedtabid as one tabs
}

ddtabcontent.getCookie=function(Name){ 
	var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
	if (document.cookie.match(re)) //if cookie found
		return document.cookie.match(re)[0].split("=")[1] //return its value
	return ""
}

ddtabcontent.setCookie=function(name, value){
	document.cookie = name+"="+value+";path=/" //cookie value is domain wide (path=/)
}

ddtabcontent.prototype={

	expandit:function(tabid_or_position){ //PUBLIC function to select a tab either by its ID or position(int) within its peers
//alert("expandit rel=" + document.getElementById(tabid_or_position).getAttribute("rel"))
		this.cancelautorun() //stop auto cycling of tabs (if running)
		var tabref=""
		//disable the tabs if -1
		if (tabid_or_position == -1) {
			for (var i=0; i<this.tabs.length; i++){ //Loop through all tabs, and assign only the selected tab the CSS class "selected"
				this.getselectedClassTarget(this.tabs[i]).className=""
			}
			return;
		}	
		try{
			if (typeof tabid_or_position=="string" && document.getElementById(tabid_or_position).getAttribute("rel")) //if specified tab contains "rel" attr
				tabref=document.getElementById(tabid_or_position)
			else if (parseInt(tabid_or_position)!=NaN && this.tabs[tabid_or_position].getAttribute("rel")) { //if specified tab contains "rel" attr
				tabref=this.tabs[tabid_or_position]
//alert("expandit tabref=" + tabref.getAttribute("rel"))
			}
		}
		catch(err){alert("Invalid Tab ID or position entered!")}
		
//alert("expandit before if=" + tabref.getAttribute("rel"))
this.expandtab(tabref) //expand this tab
//		if (tabref!="") {//if a valid tab is found based on function parameter
//alert("expandit before expandtab=" + tabref.getAttribute("rel"))
//			this.expandtab(tabref) //expand this tab
//		}
	},

	cycleit:function(dir, autorun){ //PUBLIC function to move foward or backwards through each hot tab (tabinstance.cycleit('foward/back') )
		if (dir=="next"){
			var currentTabIndex=(this.currentTabIndex<this.hottabspositions.length-1)? this.currentTabIndex+1 : 0
		}
		else if (dir=="prev"){
			var currentTabIndex=(this.currentTabIndex>0)? this.currentTabIndex-1 : this.hottabspositions.length-1
		}
		if (typeof autorun=="undefined") //if cycleit() is being called by user, versus autorun() function
			this.cancelautorun() //stop auto cycling of tabs (if running)
		this.expandtab(this.tabs[this.hottabspositions[currentTabIndex]])
	},

	setpersist:function(bool){ //PUBLIC function to toggle persistence feature
			this.enabletabpersistence=bool
	},

	setselectedClassTarget:function(objstr){ //PUBLIC function to set which target element to assign "selected" CSS class ("linkparent" or "link")
		this.selectedClassTarget=objstr || "link"
	},

	getselectedClassTarget:function(tabref){ //Returns target element to assign "selected" CSS class to
		return (this.selectedClassTarget==("linkparent".toLowerCase()))? tabref.parentNode : tabref
	},

	urlparamselect:function(tabinterfaceid){
		var result=window.location.search.match(new RegExp(tabinterfaceid+"=(\\d+)", "i")) //check for "?tabinterfaceid=2" in URL
		return (result==null)? null : parseInt(RegExp.$1) //returns null or index, where index (int) is the selected tab's index
	},

	expandtab:function(tabref){
		var subcontentid=tabref.getAttribute("rel") //Get id of subcontent to expand
		var onclickjs=tabref.getAttribute("rev") //Get id of subcontent to expand
		//Get "rev" attr as a string of IDs in the format ",john,george,trey,etc," to easily search through
//alert("expandtab subcontentid=" + subcontentid)	
//alert("rev=" + onclickjs)	
		var associatedrevids=(tabref.getAttribute("rev"))? ","+tabref.getAttribute("rev").replace(/\s+/, "")+"," : ""
		this.expandsubcontent(subcontentid)
		//borrow rev as the javascript call for onclick
		//this.expandrevcontent(associatedrevids)
		for (var i=0; i<this.tabs.length; i++){ //Loop through all tabs, and assign only the selected tab the CSS class "selected"
			this.getselectedClassTarget(this.tabs[i]).className=(this.tabs[i].getAttribute("rel")==subcontentid)? "selected" : ""
		}
		if (this.enabletabpersistence) //if persistence enabled, save selected tab position(int) relative to its peers
			ddtabcontent.setCookie(this.tabinterfaceid, tabref.tabposition)
		this.setcurrenttabindex(tabref.tabposition) //remember position of selected tab within hottabspositions[] array
		//if there is a related tabid
		if ( this.relatedtabid != undefined ) {
			relatedtabs=document.getElementById(this.relatedtabid).getElementsByTagName("a")
			for (var i=0; i<relatedtabs.length; i++){ //Loop through all tabs, and assign CSS class "" -- deactive the tabs in related tabs
				this.getselectedClassTarget(relatedtabs[i]).className=""
			}
		}
		//run the onclick function
		if ( onclickjs != undefined ) {
//alert("onclickjs=" + onclickjs)		   
			eval(onclickjs)
		}
 	},

	expandsubcontent:function(subcontentid){
//alert("expandsubcontent subcontentid=" + subcontentid)	
		for (var i=0; i<this.subcontentids.length; i++){
			var subcontent=document.getElementById(this.subcontentids[i]) //cache current subcontent obj (in for loop)
			subcontent.style.display=(subcontent.id==subcontentid)? "block" : "none" //"show" or hide sub content based on matching id attr value
//alert("subcontent.style.display=" + subcontent.style.display)	
		}
		//if there is a related tabid
		if ( this.relatedtabid != undefined ) {
//alert("this.relatedtabid=" + this.relatedtabid)	
			relatedtabs=document.getElementById(this.relatedtabid).getElementsByTagName("a")
			for (var i=0; i<relatedtabs.length; i++){ //Loop through all tabs, and assign CSS class "" -- deactive the tabs in related tabs
				var subcontent=document.getElementById(relatedtabs[i].getAttribute("rel")) //cache current subcontent obj (in for loop)
				subcontent.style.display="none";
//alert("subcontent=" + subcontent)	
//alert("subcontent.style.display=" + subcontent.style.display)
			}
		//disable help content as well	
			var helpcontent=document.getElementById("startuphelp") //cache current subcontent obj (in for loop)
			if (helpcontent!=null) {
			//alert("wha")
				helpcontent.style.display="none";
			}
		}
	},

	expandrevcontent:function(associatedrevids){
		var allrevids=this.revcontentids
		for (var i=0; i<allrevids.length; i++){ //Loop through rev attributes for all tabs in this tab interface
			//if any values stored within associatedrevids matches one within allrevids, expand that DIV, otherwise, contract it
			document.getElementById(allrevids[i]).style.display=(associatedrevids.indexOf(","+allrevids[i]+",")!=-1)? "block" : "none"
		}
	},

	setcurrenttabindex:function(tabposition){ //store current position of tab (within hottabspositions[] array)
		for (var i=0; i<this.hottabspositions.length; i++){
			if (tabposition==this.hottabspositions[i]){
				this.currentTabIndex=i
				break
			}
		}
	},

	autorun:function(){ //function to auto cycle through and select tabs based on a set interval
		this.cycleit('next', true)
	},

	cancelautorun:function(){
		if (typeof this.autoruntimer!="undefined")
			clearInterval(this.autoruntimer)
	},

	init:function(automodeperiod, setDefault){
		var persistedtab=ddtabcontent.getCookie(this.tabinterfaceid) //get position of persisted tab (applicable if persistence is enabled)
		var selectedtab=-1 //Currently selected tab index (-1 meaning none)
		var selectedtabfromurl=this.urlparamselect(this.tabinterfaceid) //returns null or index from: tabcontent.htm?tabinterfaceid=index
		this.automodeperiod=automodeperiod || 0
		for (var i=0; i<this.tabs.length; i++){
			this.tabs[i].tabposition=i //remember position of tab relative to its peers
			if (this.tabs[i].getAttribute("rel")){
				var tabinstance=this
				this.hottabspositions[this.hottabspositions.length]=i //store position of "hot" tab ("rel" attr defined) relative to its peers
				this.subcontentids[this.subcontentids.length]=this.tabs[i].getAttribute("rel") //store id of sub content ("rel" attr value)
				this.tabs[i].onclick=function(){
					tabinstance.expandtab(this)
					tabinstance.cancelautorun() //stop auto cycling of tabs (if running)
					return false
				}
				if (this.tabs[i].getAttribute("rev")){ //if "rev" attr defined, store each value within "rev" as an array element
					this.revcontentids=this.revcontentids.concat(this.tabs[i].getAttribute("rev").split(/\s*,\s*/))
				}
				if (selectedtabfromurl==i || this.enabletabpersistence && selectedtab==-1 && parseInt(persistedtab)==i || !this.enabletabpersistence && selectedtab==-1 && this.getselectedClassTarget(this.tabs[i]).className=="selected"){
					selectedtab=i //Selected tab index, if found
				}
			}
		} //END for loop
//		alert("selectedtab=" + selectedtab)
		//if (selectedtab!=-1) //if a valid default selected tab index is found
		//	this.expandtab(this.tabs[selectedtab]) //expand selected tab (either from URL parameter, persistent feature, or class="selected" class)
		//else //if no valid default selected index found
		//	this.expandtab(this.tabs[this.hottabspositions[0]]) //Just select first tab that contains a "rel" attr
		if (parseInt(this.automodeperiod)>500 && this.hottabspositions.length>1){
			this.autoruntimer=setInterval(function(){tabinstance.autorun()}, this.automodeperiod)
		}
	} //END int() function

} //END Prototype assignment