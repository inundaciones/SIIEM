package obj.CAEM
{
	[RemoteClass(alias="eventoCAEM")]
	[Bindable]
	public class eventoCAEM{
		public var id:String='0';
		public var id_cuenca:String='0';
		public var id_municipio:String='0';
		public var txt_municipio:String='0';
				
		public var id_sitio:String='0';
		public var txt_sitio:String='N/A';
		
		public var fecha:String='';
		public var id_evento:String='0';
		public var txt_evento:String='N/A';
		public var descripcion:String='';
		public var colonia:String='';
		public var L_N:String='';
		public var L_S:String='';
		public var L_O:String='';
		public var L_P:String='';
		public var superficie:String='0';
		public var unidad:String='M2';
		public var poblacion:String='0';
		public var casa:String='0';
		public var local:String='0';
		public var industria:String='0';
		public var otros:String='0';
		public var publico:String='0';
		public var t_vial:String='0';
		public var t_interior:String='0';
		
		public var lat:String='0';
		public var lon:String='0';
		
		public var uri_user:String='';
		public var txt_user:String='';
		public var user_type:String='';
		
		public var causas:Array=new Array();
		public var acciones:Array=new Array();		
		public var observaciones:Array=new Array();
		public var files:Array=new Array();
		public var file_count:Number=0;
		public var last_update:String='';
		
		public var prioridad:Number=1;
		
	}
}