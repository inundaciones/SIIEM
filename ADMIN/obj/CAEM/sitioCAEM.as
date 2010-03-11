package obj.CAEM
{
	[RemoteClass(alias="sitioCAEM")]
	[Bindable]
	public class sitioCAEM
	{
		public var id_sitio:String;
		public var id_cuenca:String;
		public var id_municipio:String;
		public var txt_municipio:String;
		public var txt_gerencia:String;
		public var name:String;
		
		public var clave:String;
		public var D_N:String;
		public var D_S:String;
		public var D_O:String;
		public var D_P:String;
		
		public var lat:String;
		public var lon:String;
		
		public var poblacion:String;
		public var reincidencia:String;
	}
}