package obj
{
	import mx.collections.ArrayCollection;

	public class GrafPoblacion extends ArrayCollection
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
		/*[Bindable]
		public var seis:Object= new Object();
		[Bindable]
		public var siete:Object= new Object();*/
		public function GrafPoblacion(source:Array=null)
		{
			super(source);
			uno.name='0-100';
			uno.datos=3;
			this.addItem(uno);
			dos.name='101-1,000';
			dos.datos=Math.random()*100;
			this.addItem(dos);
			tres.name='1001-5,000';
			tres.datos=Math.random()*100;
			this.addItem(tres);
			cuatro.name='5001-10,000';
			cuatro.datos=Math.random()*100;
			this.addItem(cuatro);
			cinco.name='más de 10,000';
			cinco.datos=Math.random()*100;
			this.addItem(cinco);
			/*seis.name='6';
			seis.datos=Math.random()*100;
			this.addItem(seis);
			siete.name='7';
			siete.datos=Math.random()*100;
			this.addItem(siete);*/
		}
		
	}
}