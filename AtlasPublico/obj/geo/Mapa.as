package obj.geo
{
	import com.google.maps.LatLng;
	import com.google.maps.Map;
	import com.google.maps.MapEvent;
	import com.google.maps.MapMouseEvent;
	import com.google.maps.interfaces.IOverlay;
	import com.google.maps.interfaces.IPane;
	import com.google.maps.interfaces.IPaneManager;
	import com.google.maps.overlays.Polygon;
	
	
	
	public class Mapa extends Map
	{
		public var pintando:Boolean= false;
		private var polyLatLngs:Array = new Array();
		private var polyShape:Polygon;
		
		private var inundaciones:IPane;
		private var manager:IPaneManager;
		
		private var polys:Array= new Array();
		private var pp:int=0;
		
		[Bindable]
		private var sitios:Array= new Array();
		
		public function Mapa()
		{
			
			super();
			
			addEventListener(MapEvent.MAP_READY, onMapReady);
			this.addEventListener(MapMouseEvent.CLICK,mouseMove);
			
		}
		
		public function newPoly():void
		{
			pp++;
			polyLatLngs = new Array();
			manager.createPane(pp);
			
		}
		
		private function onMapReady(event:MapEvent):void {
		 	manager=this.getPaneManager();
		 	//manager.createPane(0);
			enableScrollWheelZoom();
		}
		
		
		private function mouseMove(e:MapMouseEvent):void
		{
			
			if(pintando)
			{
				var latlngClicked:LatLng = e.latLng;
                polyLatLngs.push(latlngClicked);
                drawCoordinates();
			}
			
		}
		
		private function drawCoordinates():void {
         // clearOverlays();
           if (polyLatLngs.length > 1) {
              
              polyShape = new Polygon(polyLatLngs);
             // addOverlay(polyShape);
                var pane:IPane = manager.getPaneAt(pp);
			    pane.clear();
			    if (pane) {
			      pane.addOverlay(polyShape);
			    } 
           }
           
           
       }
       
       public function addMarca(mm:IOverlay):void
       {
       		sitios.push(mm);
       		this.addOverlay(mm);
       }
       
       public function ocultar(inx:int):void
       {
       	if(sitios[inx]!=null)
       	{
       		(sitios[inx] as IOverlay).visible=false;
       	}
       }
       
       public function ocultarTodos():void
       {
       	for(var i:int=0;i<sitios.length;i++)
       	{
       		if(sitios[i]!=null)
	       	{
	       		(sitios[i] as IOverlay).visible=false;
	       	}
       	}
       }
       
       public function mostrarTodos():void
       {
       		for(var i:int=0;i<sitios.length;i++)
       	{
       		if(sitios[i]!=null)
	       	{
	       		(sitios[i] as IOverlay).visible=true;
	       	}
       	}
       }
	}
}