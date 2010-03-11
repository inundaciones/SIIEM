package obj
{
	import flash.events.Event;
	
	import obj.CAEM.sitioCAEM;

	public class EventMarker extends Event
	{
		public var sitio:sitioCAEM= new sitioCAEM();
		public function EventMarker(type:String, bubbles:Boolean=false, cancelable:Boolean=false, elSitio:sitioCAEM=null)
		{
			sitio=elSitio;
			super(type, bubbles, cancelable);
		}
		
	}
}