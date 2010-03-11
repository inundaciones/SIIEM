package obj.geo
{
	import flash.display.DisplayObject;
	import flash.display.Sprite;
	import flash.geom.ColorTransform;
	import flash.text.TextField;
	import flash.text.TextFormat;

	public class Icono extends Sprite
	{
		[Embed(source="assets/gota_blanca.svg")]
   		private var _markerIcon:Class;
   		private var ic:DisplayObject;
   		
   		private var reDato:Number= new Number();
   		private var poDato:Number= new Number();
   		
   		private var txt:TextField= new TextField();
   		
   		
   		
		public function Icono(pob:String="1",rein:String="1")
		{
			
			super();
			reDato=parseFloat(rein);
			poDato=parseFloat(pob);
			ic= new _markerIcon();
			
			ic.x=-15;
			ic.y=-25;
			ic.cacheAsBitmap=true;
			var format:TextFormat = new TextFormat();
            format.font = "Arial";
            format.color = 0xFFFFFF;
            format.size = 12;
			txt.defaultTextFormat=format;
			txt.text=reDato.toString();
			txt.width=12;
			txt.height=16;
			txt.selectable=false;
			txt.x=-14;
			txt.y=-16;
		
			preparar();
			
		}
		
		private function preparar():void
		{
			var cc:Number=reDato/7;
			ic.transform.colorTransform=new ColorTransform(cc,1-cc,0,1,0,0,0,0);
			//ic.alpha=0.6;
			addChild(ic);
			addChild(txt);
			this.cacheAsBitmap=true;
			//this.alpha=reDato;
			this.scaleX=this.scaleY=poblacion(poDato);
			
			
		}
		
		private function reincidencia(n:Number):uint
		{
			var color:uint=0xFF00FF;
			
			return color;			
		}
		
		private function poblacion(p:Number):Number
		{
			var escala:Number= new Number();
			if(p<=100)
			{
				escala=0.4;
			}else if(p>100 && p<=1000)
			{
				escala=0.6;
			}else if(p>1000 && p<=5000)
			{
				escala=0.8;
			}else if(p>5000 && p<=10000)
			{
				escala=0.9;
			}else
			{
				escala=1.3;
			}
			return escala;
		}
		
	}
}