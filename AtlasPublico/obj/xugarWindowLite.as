package obj{
	import flash.events.Event;
	import flash.events.MouseEvent;
	
	import mx.containers.Panel;
	import mx.controls.Button;
	import mx.core.UIComponent;
	
	public class xugarWindowLite extends Panel{
		
		//COMPONENTES DE LA BARRA DE TITULO
		private var myTitleBar:UIComponent;
		private var closeButton:Button	=new Button();
		private var minButton:Button	=new Button();
		private var restoreButton:Button=new Button();
		private var maxButton:Button	=new Button();
		
		//Mantiene El Tamaño Original
		private var oW:Number;
		private var oH:Number;		
		
		public var close_hide:String='close';		
		public var showMax:Boolean=false;
		public var minX:Number=0;
		public var minY:Number=0;
		
		public function xugarWindowLite(){			
			
		}
		
		override protected function createChildren():void{
			
			super.createChildren();
			
			//CREA LA BARRA DE TITULO
			this.myTitleBar=super.titleBar;
			this.myTitleBar = super.titleBar;                       
			this.myTitleBar.alpha=1;
			this.myTitleBar.doubleClickEnabled=true;									                        
			                         
			//CREA EL BOTON MAXIMIZAR			
            this.maxButton.width         = 18;
            this.maxButton.height        = 18;
            this.maxButton.styleName     = "maxBtn";
            if(this.showMax==true){
            	this.myTitleBar.addChild(this.maxButton);
            }
            
			//CREA EL BOTON DE MINIMIZAR
			this.minButton.width         = 18;
            this.minButton.height        = 18;
            this.minButton.styleName     = "minBtn";
            this.myTitleBar.addChild(this.minButton);
            
            //CREA EL BOTON DE RESTAURAR
			this.restoreButton.width         = 0;
            this.restoreButton.height        = 0;
            this.restoreButton.styleName     = "restoreBtn";
            this.myTitleBar.addChild(this.restoreButton);

            //CREA EL BOTON DE CERRAR            
            this.closeButton.width             = 18;
            this.closeButton.height            = 18;
            this.closeButton.styleName         = "closeBtn";                
            this.myTitleBar.addChild(this.closeButton);
			
			this.oH=this.height;
			this.oW=this.width;
			this.positionChildren();
			this.addListeners();
			
		}
		
		
		//POSICIONA LOS BOTONES
		public function positionChildren():void {           
            	
                //POSICIONA RESTAURAR SOBRE MINIMIZAR Y LO HACE INVISIBLE
                this.restoreButton.buttonMode    = true;
                this.restoreButton.useHandCursor = true;
                this.restoreButton.x = this.unscaledWidth- 40;
                this.restoreButton.y = 4;                
            	
            	//POSICIONA MAXIMIZAR
                this.maxButton.buttonMode    = true;
                this.maxButton.useHandCursor = true;
                this.maxButton.x = this.unscaledWidth  - 60;
                this.maxButton.y = 4;
                
                //POSICIONA MINIMIZAR
                this.minButton.buttonMode    = true;
                this.minButton.useHandCursor = true;
                this.minButton.x = this.unscaledWidth  - 40;
                this.minButton.y = 4;
                                                
                //POSICIONA CERRAR
                this.closeButton.buttonMode     = true;
                this.closeButton.useHandCursor	= true;
                this.closeButton.x = this.unscaledWidth - 20;
                this.closeButton.y = 4;
          
        }

		 private function addListeners():void {                                    
            this.closeButton.addEventListener(MouseEvent.CLICK, closeMe);
            this.maxButton.addEventListener(MouseEvent.CLICK, maxMe);
            this.minButton.addEventListener(MouseEvent.CLICK, minMe);   
            this.restoreButton.addEventListener(MouseEvent.CLICK, restoreMe);
            this.titleBar.addEventListener(MouseEvent.MOUSE_OVER,titleOverHandler);
            this.titleBar.addEventListener(MouseEvent.MOUSE_OUT,titleOutHandler);
            this.titleBar.addEventListener(MouseEvent.MOUSE_DOWN,titleDownHandler);
            this.titleBar.addEventListener(MouseEvent.MOUSE_UP,titleUpHandler);
            this.titleBar.addEventListener(MouseEvent.DOUBLE_CLICK,titleDoubleClickHandler);
            this.addEventListener(Event.ENTER_FRAME,enterFrameHandler);            
        }
        
        private function titleOutHandler(event:MouseEvent):void{
        	//this.titleBar.alpha=0.6;
        }
        
        private function titleOverHandler(event:MouseEvent):void{
        	//	this.titleBar.alpha=1;
        }
        
         private function titleDownHandler(event:MouseEvent):void{
        	this.startDrag();
        }
        
        private function titleUpHandler(event:MouseEvent):void{
        	this.stopDrag();        	
        }
                        
        private function titleDoubleClickHandler(event:MouseEvent):void{
        	if(this.height==this.titleBar.height){
        		this.restoreMe(null);
        	}else{
        		this.minMe(null);
        	}
        }
        
        private function enterFrameHandler(event:Event):void{
        	if(this.x+this.width>=this.stage.width){
        		this.x=this.stage.width-this.width;
        	}
        	if(this.y+this.height>=this.stage.height){
        		this.y=this.stage.height-this.height;
        	}
        	if(this.y<this.minY){
        		this.y=this.minY;
        		this.stopDrag();
        	}
        	if(this.x<this.minX){
        		this.stopDrag();
        		this.x=this.minX;
        	}
        }
        
        public function closeMe(event:MouseEvent):void{
        	if(this.close_hide=='close'){
        		this.parent.removeChild(this);
        	}else{
        		this.visible=false;
        	}	
        }
        
        public function maxMe(event:MouseEvent):void{
        	this.restoreButton.width=18;
        	this.restoreButton.height=18;        	
        	this.width=this.stage.width;
        	this.height=this.stage.height;
        	this.positionChildren();
        	this.maxButton.visible=false;
        	this.restoreButton.visible=true;
        	this.restoreButton.x=this.maxButton.x;        	
        }
        
        public function minMe(event:MouseEvent):void{
        	this.restoreButton.width=18;
        	this.restoreButton.height=18;        	
        	this.height=this.titleBar.height;        	
        	this.minButton.visible=false;
        	this.restoreButton.visible=true;
        	this.restoreButton.x=this.minButton.x;        	
        }
        
        public function restoreMe(event:MouseEvent):void{
        	this.restoreButton.width=0;
        	this.restoreButton.height=0;
        	this.height=this.oH;
        	this.width=this.oW;
        	this.positionChildren();
        }
        
        
        
	}
}