package obj
{
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	import flash.geom.*;
	
	import mx.core.IToolTip;
	import mx.managers.ToolTipManager;

	public class MarcadorSitio extends Sprite
	{
		public var nombreSitio:String= new String();
		public var nombreLocalidad:String= new String();
		public var color:Number= 0xdd0000;
		
		public var tT:IToolTip;
		
		public function MarcadorSitio(l1:String="",l2:String="")
		{
			nombreSitio=l1;
			nombreLocalidad=l2;
			var c:Sprite= new Sprite();
				c.graphics.beginFill(color,1);
				c.graphics.drawCircle(-2.5,-2.5,5);
				c.graphics.endFill();
			addChild(c);
			
			this.addEventListener(MouseEvent.MOUSE_OVER, overThis);
			this.addEventListener(MouseEvent.MOUSE_OUT,outThis);
		}
		
		
		private function overThis(event:MouseEvent):void
		{
			var texto:String=nombreSitio+"\n"+nombreLocalidad;
			tT=ToolTipManager.createToolTip(texto,stage.mouseX, stage.mouseY);
			
			
		}
		
		private function outThis(event:MouseEvent):void
		{
			ToolTipManager.destroyToolTip(tT);
		}
		
		
	}
}