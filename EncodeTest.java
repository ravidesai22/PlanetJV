public class EncodeTest{

  public static void main(String args[]){
		String defaultCharacterEncoding = System.getProperty("file.encoding");
		System.out.println("defaultCharacterEncoding == " +defaultCharacterEncoding);
		
		System.setProperty("file.encoding","UTF-8");
		defaultCharacterEncoding = System.getProperty("file.encoding");
		System.out.println("After modifying externally == " +defaultCharacterEncoding);
		
		String name = new String("愛佳愛子");
		System.out.println("Name == " +name);
		
		//byteTest("ÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ");
		byteTest("ÒÞßñÿ");
		byteTest("愛佳愛子");
		byteTest("çéñ");

	}
	
	static void byteTest(String s1){
		try{
		
			System.out.println("ISO-8859-1 :");	
			byte [] b1 = s1.getBytes("ISO-8859-1");
			for(int i=0;i<b1.length;i++){
				System.out.println(b1[i]);
			}
		
			System.out.println("UTF 8 :");
			byte [] b2 = s1.getBytes("UTF-8");
			for(int i=0;i<b2.length;i++){
				System.out.println(b2[i]);
			}
		
			String s = new String(s1.getBytes("ISO-8859-1"), "UTF-8");
			//String s = new String(s1.getBytes("UTF-8"), "UTF-8");
			//String s = new String(s1.getBytes("UTF-8"), "ISO-8859-1");
			System.out.println("new string==> " +s + "\n");
		}catch(Exception e){
			System.out.println("Exception");
		}
	}
	
	public static boolean isWithinUTF8(String input)
	{
		// assuming that the text is valid unless it is not
		boolean results = true;
		
		/*if (input == null) {input="";}
		input = input.trim();*/

		byte byteArr[];		

		 try {
			 byteArr = input.getBytes("UTF-8");		
				input = new String (byteArr, "UTF-8");
	            Charset.availableCharsets().get("UTF-8").newDecoder().decode(ByteBuffer.wrap(byteArr));  
	  
	        } catch (CharacterCodingException e) {	  
	        	results = false; 
	        } catch (UnsupportedEncodingException e) {
	        	results = false; 
				e.printStackTrace();
			}  
	  
	     System.out.println("****************" + results);
		return results;
	}

}
