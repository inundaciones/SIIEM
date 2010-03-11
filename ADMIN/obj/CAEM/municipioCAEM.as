package obj.CAEM
{
	import mx.controls.Alert;
	import mx.managers.CursorManagerPriority;
	import mx.managers.CursorManager;
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.remoting.mxml.RemoteObject;
			
	[RemoteClass(alias="municipioCAEM")]
	[Bindable]
	public class municipioCAEM
	{
	
		[Embed(source="assets/cursor.swf")]
		private var cursorCAEM:Class;
		
		public var id_municipio:String;
		public var name:String;
		public var id_cuenca:String;		
		public var txt_cuenca:String;
		public var id_gerencia:String;
		public var txt_gerencia:String;
		public var sitios:Array;		
		public var directorio:Array;		
		
		
		/*public function loadLocalidades():void{
			this.loadSitios=loadSitios;
			var ro:RemoteObject=new RemoteObject('zend');
			ro.source='catalogosGeograficos';
			ro.addEventListener(ResultEvent.RESULT,loadLocalidadesResultHandler);
			ro.addEventListener(FaultEvent.FAULT,loadLocalidadesFaultHandler);
			ro.loadLocalidades(this.id_municipio);
			CursorManager.setCursor(cursorCAEM,CursorManagerPriority.HIGH);
		}
		
		
		private function loadLocalidadesFaultHandler(event:FaultEvent):void{
			Alert.show(event.fault.message, "Error");
			CursorManager.removeCursor(CursorManager.currentCursorID);	
		}
		
		private function loadLocalidadesResultHandler(event:ResultEvent):void{
			this.localidades=event.result as Array;			
			CursorManager.removeCursor(CursorManager.currentCursorID);
		}*/
		
		public function loadDirectorio():void{			
			var ro:RemoteObject=new RemoteObject('zend');
			ro.source='catalogosGenerales';
			ro.addEventListener(ResultEvent.RESULT,loadDirectorioResultHandler);
			ro.addEventListener(FaultEvent.FAULT,loadDirectorioFaultHandler);
			ro.loadDirectorio(this.id_municipio);
			CursorManager.setCursor(cursorCAEM,CursorManagerPriority.HIGH);
		}
		
		
		private function loadDirectorioFaultHandler(event:FaultEvent):void{
			Alert.show(event.fault.message, "Error");
			CursorManager.removeCursor(CursorManager.currentCursorID);	
		}
		
		private function loadDirectorioResultHandler(event:ResultEvent):void{
			this.directorio=event.result as Array;			
			CursorManager.removeCursor(CursorManager.currentCursorID);
		}

	}
}