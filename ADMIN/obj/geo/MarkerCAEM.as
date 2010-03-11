package obj.geo
{
	import com.google.maps.LatLng;
	import com.google.maps.MapMouseEvent;
	import com.google.maps.overlays.Marker;
	import com.google.maps.overlays.MarkerOptions;
	
	import obj.CAEM.sitioCAEM;
	

	public class MarkerCAEM extends Marker
	{
		public var sitio:sitioCAEM= new sitioCAEM();
		
		public function MarkerCAEM(arg0:LatLng, arg1:MarkerOptions=null)
		{
			super(arg0, arg1);
					
		}
		
		
		
	}
}