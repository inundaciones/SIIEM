package obj.CAEM
{
	import mx.controls.Alert;
	import mx.managers.CursorManager;
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.remoting.mxml.RemoteObject;
		
	[RemoteClass(alias="cuencaCAEM")]
	[Bindable]	
	public class cuencaCAEM
	{
		public var id_cuenca:String;
		public var name:String;								
		public var municipios:Array;
		
		
	}
}