package obj.geo
{
	import com.google.maps.LatLng;
	import com.google.maps.LatLngBounds;
	import com.google.maps.Map;
	import com.google.maps.MapEvent;
	import com.google.maps.MapMouseEvent;
	import com.google.maps.PaneId;
	import com.google.maps.controls.ControlPosition;
	import com.google.maps.controls.MapTypeControl;
	import com.google.maps.controls.ZoomControl;
	import com.google.maps.controls.ZoomControlOptions;
	import com.google.maps.interfaces.IPane;
	import com.google.maps.interfaces.IPaneManager;
	import com.google.maps.overlays.Polygon;
	
	import mx.controls.Alert;
	import mx.controls.Label;
	import mx.managers.CursorManager;
	import mx.managers.CursorManagerPriority;
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.remoting.RemoteObject;
	
	import obj.snapShot;
	
	
	public class Mapa extends Map{
	
	
	    [Embed(source="assets/cursor.swf")]
		private var cursorCAEM:Class;
		
		[Bindable]
		public var pintando:Boolean= false;
		public var mouseLatLng:LatLng;
		
				
		private var tmpLatLngs:Array = new Array();		
		
		private var inundaciones:IPane;
		private var tmpPane:IPane;		
		
		private var id_evento:String="0";
		private var manager:IPaneManager;		

		private var centerLL:Label=new Label();
						
		public function Mapa(){	
			super();			
			this.addEventListener(MapEvent.MAP_READY, onMapReady);
			this.addEventListener(MapMouseEvent.CLICK,mouseClickHandler);
			this.addEventListener(MapMouseEvent.MOUSE_MOVE,mouseMoveHandler);						
		}
						
		private function onMapReady(event:MapEvent):void {			
		 	var zco:ZoomControlOptions=new ZoomControlOptions();
			zco.position=new ControlPosition(ControlPosition.ANCHOR_TOP_RIGHT,10,40);				
			
			var zC:ZoomControl=new ZoomControl(zco);				
			this.addControl(zC);			
			this.addControl(new MapTypeControl);
			this.enableScrollWheelZoom();
					 	
		 	this.manager=this.getPaneManager();
		 	this.inundaciones=manager.getPaneById(PaneId.PANE_OVERLAYS);
		 	this.tmpPane=manager.createPane(manager.paneCount);
		 		 	
		}
		
		
		public function newPoly():void{			
			this.pintando=true;		
			this.tmpLatLngs=new Array();	
		}
		
		public function savePoly(id_evento:String):void{			
			var p:geoPoly=new geoPoly();
			
			for each(var l:LatLng in this.tmpLatLngs){
				var gp:geoPoint=new geoPoint();
				gp.x=l.lat().toString();
				gp.y=l.lng().toString();
				p.puntos.push(gp);
			}
			p.id_evento=id_evento;
			var ro:RemoteObject=new RemoteObject('zend');
			ro.source="geoController";
			ro.addEventListener(ResultEvent.RESULT,saveResultHandler);
			ro.addEventListener(FaultEvent.FAULT,faultHandler);
			ro.savePoly(p);
			
			this.pintando=false;
			this.tmpPane.clear();
			this.drawPoly(this.tmpLatLngs);
			
			this.id_evento=id_evento;
		
			
			callLater(polySnapShot);
			
			
			CursorManager.setCursor(cursorCAEM,CursorManagerPriority.HIGH);
						
		}
		
		private function polySnapShot():void{
			var ss:snapShot=new snapShot(this);
			ss.wwwSaveJPEG(100, (this.parentApplication as admin_atlas).preURL+"files/snapshot.php",id_evento+".jpg");
		}
		
		private function saveResultHandler(event:ResultEvent):void{
			CursorManager.removeCursor(CursorManager.currentCursorID);
		
		}
		
		public function loadPoly(id_evento:String):void{
			this.inundaciones.clear();
			var ro:RemoteObject=new RemoteObject('zend');
			ro.source="geoController";
			ro.addEventListener(ResultEvent.RESULT,loadResultHandler);
			ro.addEventListener(FaultEvent.FAULT,faultHandler);
			CursorManager.setCursor(cursorCAEM,CursorManagerPriority.HIGH);
			ro.loadPoly(id_evento);
		}
		
		private function loadResultHandler(event:ResultEvent):void{
			
			var a:Array=event.result as Array;
			var llb:LatLngBounds=new LatLngBounds();
			for each(var p:geoPoly in a){
				var tmpLL:Array=new Array();
				for each(var gp:geoPoint in p.puntos){
					var ll:LatLng=new LatLng(parseFloat(gp.x),parseFloat(gp.y));
					tmpLL.push(ll);
					llb.extend(ll);
				}
				this.drawPoly(tmpLL);
			}
			if(llb.getCenter().lat()!=0&&llb.getCenter().lng()!=0){
				this.setCenter(llb.getCenter());
				this.setZoom(this.getBoundsZoomLevel(llb));
			}	
			CursorManager.removeCursor(CursorManager.currentCursorID);
		}
		
		private function faultHandler(event:FaultEvent):void{
			CursorManager.removeCursor(CursorManager.currentCursorID);
			Alert.show(event.fault.toString());
		}
		
		public function cancelPoly():void{
			this.pintando=false;	
			this.tmpPane.clear();				
		}
				
		private function mouseClickHandler(e:MapMouseEvent):void{			
			if(pintando){				
                this.tmpLatLngs.push(e.latLng);
                this.drawPoly(this.tmpLatLngs,true);
			}			
		}
		
		private function mouseMoveHandler(e:MapMouseEvent):void{						
			if(pintando){			
				var myArray:Array=new Array();
				for(var i:Number=0;i<this.tmpLatLngs.length;i++){
					myArray.push(this.tmpLatLngs[i]);
				}	
                myArray.push(e.latLng);
                this.drawPoly(myArray,true);
			}
			this.mouseLatLng=e.latLng;			
		}
		
		
		
		private function drawPoly(points:Array,tmp:Boolean=false):void {         
                var p:Polygon=new Polygon(points);
                                
                if(tmp){
                	this.tmpPane.clear();
                	this.tmpPane.addOverlay(p);	
                }else{
                	this.inundaciones.addOverlay(p);
                }	           
       }
       
	}
}