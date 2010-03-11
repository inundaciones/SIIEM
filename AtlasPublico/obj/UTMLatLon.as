package obj
{
	public class UTMLatLon
	{
		
		public var Lat:Number;//x
		public var Lon:Number;//y
		public var UTMx:Number;
		public var UTMy:Number;		
		
		public var A:Number;//Radio Ecuatorial
		public var B:Number;//Radio Polar
		public var Flattening:Number;
		public var E:Number;//Excentricidad de la tierra
		public var E2:Number;//No se que carajos sea aun
		public var N:Number;
		public var RHO:Number;//Radio de curvatura de la tierra en el plano meridonial
		
		private var k0:Number=0.9996;				
		private var long0:Number=-099.0;
		
		public function UTMLatLon(A:Number,B:Number){
			this.A=A;
			this.B=B;
			this.Flattening=(A-B)/A;
			this.E=Math.sqrt(1-(Math.pow(B,2)/Math.pow(A,2)));
			this.E2=Math.pow(E,2)/(1-Math.pow(E,2));
			this.N=(A-B)/(A+B);	
			
			
		}
		
		public function LatLonToUTM():void{
			var latR:Number=this.toRad(Lat);
			var lonR:Number=this.toRad(Lon);
			
			var P:Number=lonR-this.toRad(long0);
			
			var A0:Number=A*(1-N+(5*N*N/4)*(1-N)+(81*Math.pow(N,4)/64)*(1-N));
			var B0:Number=(3*A*N/2)*(1-N-(7*N*N/8)*(1-N)+55*Math.pow(N,4)/64);
			var C0:Number=(15*A*N*N/16)*(1-N+(3*N*N/4)*(1-N));
			var D0:Number=(35*A*Math.pow(N,3)/48)*(1-N+11*N*N/16);
			var E0:Number=(315*A*Math.pow(N,4)/51)*(1-N);
			
			var NU:Number = A/Math.sqrt((1-E2*Math.sin(latR)*Math.sin(latR)));
			
			var S:Number=A0*latR - B0*Math.sin(2*latR) + C0*Math.sin(4*latR) - D0*Math.sin(6*latR) + E0*Math.sin(8*latR);
			
			var K1:Number = S*k0;
			var K2:Number= k0*NU*Math.sin(2*latR)/4;
			var K3:Number = (k0*NU*Math.sin(latR)*Math.pow(Math.cos(latR),3)/24)*((5 - Math.pow(Math.tan(latR),2) + 9*Math.pow(E0,2)*Math.pow(Math.cos(latR),2) + 4*Math.pow(E0,4)*Math.pow(Math.cos(latR),4)));
			var K4:Number = k0*NU*Math.cos(latR);
			var K5:Number = (k0*NU*Math.pow(Math.cos(latR),3)/6)*(1 - Math.pow(Math.tan(latR),2) + E0*E0*Math.pow(Math.cos(latR),2));
			
			
			this.UTMy=K1+K2*Math.pow(P,2)+K3*Math.pow(P,4);
			this.UTMx=K4*P + K5*Math.pow(P,3);
			
		}
		
		public function UTMToLatLon():void{
			
			var realx:Number=500000-UTMx;
			var M:Number=UTMy/k0;
			var MU:Number=M/(A*(1 - Math.pow(E,2)/4 - 3*Math.pow(E,4)/64 - 5*Math.pow(E,6)/256));
			var E1:Number = (1 - Math.sqrt(1 - Math.pow(E,2)))/(1 + Math.sqrt(1 - Math.pow(E,2)));
			
			var J1:Number = (3*E1/2 - 27*Math.pow(E1,3)/32);
			var J2:Number = (21*Math.pow(E1,2)/16 - 55*Math.pow(E1,4)/32);
			var J3:Number = (151*Math.pow(E1,3)/96);
			var J4:Number = (1097*Math.pow(E1,4)/512);
			
			var FP:Number=MU + J1*Math.sin(2*MU) + J2*Math.sin(4*MU) + J3*Math.sin(6*MU) + J4*Math.sin(8*MU);
			
			var C1:Number=E2*Math.pow(Math.cos(FP),2);
			var T1:Number=Math.pow(Math.tan(FP),2);
			var R1:Number=(A*(1-Math.pow(E,2))/Math.sqrt(Math.pow((1-Math.pow(E,2)*Math.pow(Math.sin(FP),2)),3)));
			var N1:Number=A/Math.sqrt(1-Math.pow(E,2)*Math.pow(Math.sin(FP),2));
			var D:Number=realx/(N1*k0);
			
			var Q1:Number=N1*Math.tan(FP)/R1;
			var Q2:Number=Math.pow(D,2)/2;
			var Q3:Number=((5+3*T1+10*C1-4*Math.pow(C1,2)-9*E2)*Math.pow(D,4))/24;
			var Q4:Number=((61+90*T1+298*C1+45*Math.pow(T1,2)-3*Math.pow(C1,2)-252*E2)*Math.pow(D,6))/720;
			
			this.Lat =180*( FP - Q1*(Q2 - Q3 + Q4))/Math.PI;
			
			var Q5:Number=D;
			var Q6:Number=((1+2*T1+C1)*Math.pow(D,3))/6;
			var Q7:Number=((5-2*C1+28*T1-3*Math.pow(C1,2)+8*E2+24*Math.pow(T1,2))*Math.pow(D,5))/120;
			
			this.Lon=long0 - ((Q5 - Q6 + Q7)/Math.cos(FP))*180/Math.PI;
			
		}
		
		private function toRad(G:Number):Number{
			return ((G/180)*Math.PI);
			
		}
		
	}
	
}