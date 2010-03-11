package obj.CAEM
{
	import mx.controls.Alert;
	import mx.managers.CursorManager;
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.remoting.mxml.RemoteObject;
	
	[RemoteClass(alias="localidadCAEM")]
	[Bindable]
	public class localidadCAEM
	{
		public var id_localidad:String;
		public var id_municipio:String;
		public var ambito:String;
		public var lat:String;
		public var lon:String;
		public var name:String;		
		public var sitios:Array;
		
		public function loadSitios():void{
			var ro:RemoteObject=new RemoteObject('zend');
			ro.source='catalogosGeograficos';
			ro.addEventListener(ResultEvent.RESULT,loadSitiosResultHandler);
			ro.addEventListener(FaultEvent.FAULT,loadSitiosFaultHandler);
			ro.loadSitios(this.id_localidad);
			CursorManager.setCursor(cursorCAEM,CursorManagerPriority.HIGH);
		}
		
			
		private function loadSitiosFaultHandler(event:FaultEvent):void{
			Alert.show(event.fault.message, "Error");
			CursorManager.removeCursor(CursorManager.currentCursorID);	
		}
		
		private function loadSitiosResultHandler(event:ResultEvent):void{
			this.sitios=event.result as Array;			
			CursorManager.removeCursor(CursorManager.currentCursorID);
		}
		
	}
}