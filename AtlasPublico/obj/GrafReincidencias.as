package obj
{
	import mx.collections.ArrayCollection;

	public class GrafReincidencias extends ArrayCollection
	{
		[Bindable]
		public var uno:Object= new Object();
		[Bindable]
		public var dos:Object= new Object();
		[Bindable]
		public var tres:Object= new Object();
		[Bindable]
		public var cuatro:Object= new Object();
		[Bindable]
		public var cinco:Object= new Object();
		[Bindable]
		public var seis:Object= new Object();
		[Bindable]
		public var siete:Object= new Object();
		
		public function GrafReincidencias(source:Array=null)
		{
			super(source);
			uno.name='1';
			uno.datos=3;
			this.addItem(uno);
			dos.name='2';
			dos.datos=Math.random()*100;
			this.addItem(dos);
			tres.name='3';
			tres.datos=Math.random()*100;
			this.addItem(tres);
			cuatro.name='4';
			cuatro.datos=Math.random()*100;
			this.addItem(cuatro);
			cinco.name='5';
			cinco.datos=Math.random()*100;
			this.addItem(cinco);
			seis.name='6';
			seis.datos=Math.random()*100;
			this.addItem(seis);
			siete.name='7';
			siete.datos=Math.random()*100;
			this.addItem(siete);
		}
		
	}
}