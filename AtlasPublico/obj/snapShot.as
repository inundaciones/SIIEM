package obj
{
	import flash.display.BitmapData;
	import flash.geom.Matrix;
	import flash.net.FileReference;
	import flash.net.URLVariables;
	import flash.utils.ByteArray;
	
	import mx.core.UIComponent;
	import mx.graphics.codec.JPEGEncoder;
	import mx.graphics.codec.PNGEncoder;
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.HTTPService;
	import mx.utils.Base64Encoder;
	
	public class snapShot{
		
		private var myBitmapData:BitmapData;
		private var myByteArray:ByteArray=new ByteArray();
		private var myString:String="";
		
		private var m:Matrix = new Matrix();
		
		public function snapShot(source:UIComponent){
			myBitmapData=new BitmapData(source.width, source.height);			
			myBitmapData.draw(source,m);		
		}
		
		private function encodeToJPEG(quality:Number=75):void{
			var encoder:JPEGEncoder=new JPEGEncoder(quality);
			this.myByteArray=encoder.encode(this.myBitmapData);
		}
		
		private function encodeToPNG():void{
			var encoder:PNGEncoder=new PNGEncoder();
			this.myByteArray=encoder.encode(this.myBitmapData);	
		}
		
		private function base64Encode():void{
			var encoder:Base64Encoder=new Base64Encoder();
			encoder.encodeBytes(this.myByteArray);
			this.myString=encoder.flush();
		}
		
		public function wwwSavePNG(URL:String,name:String):void{
			this.encodeToPNG();
			this.base64Encode();
			this.wwwSave(URL,name);
		}
		
		public function wwwSaveJPEG(quality:Number,URL:String, name:String):void{
			this.encodeToJPEG(quality);
			this.base64Encode();
			this.wwwSave(URL,name);
		}
		
		private function wwwSave(URL:String,name:String):void{
			var vars:URLVariables=new URLVariables();
			vars.file=this.myString;
			vars.NAME=name;
			var request: HTTPService=new HTTPService();
			request.url=URL;
			request.method="POST";
			request.resultFormat='e4x';
			request.request=vars;
			request.addEventListener(ResultEvent.RESULT,wwwSaveResultHandler);
			request.addEventListener(FaultEvent.FAULT,wwwSaveFaultHandler);
			request.send();			
		}
		
		private function wwwSaveResultHandler(event:ResultEvent):void{
			var x:XML=event.result as XML;
		}
		
		private function wwwSaveFaultHandler(event:FaultEvent):void{
			var x:XML=event.fault as XML;
		}
				
		
	}
}