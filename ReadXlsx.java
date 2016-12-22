package excel;
import org.apache.poi.ss.usermodel.Cell;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.xssf.usermodel.XSSFCell;
import org.apache.poi.xssf.usermodel.XSSFRow;
import org.apache.poi.xssf.usermodel.XSSFSheet;
import org.apache.poi.xssf.usermodel.XSSFWorkbook;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Iterator;

import javax.swing.JFileChooser;
import javax.swing.JOptionPane;

public class ReadXlsx {
	private String connectionURL = "JDBC:mysql://localhost/quality_enhancement_cell"; 
	private Connection conn=null;
	private Statement st=null;
	private String filePath;
	private static ArrayList<data> Data=new ArrayList<>();
	public void punctuateStrings(String s,String[] split){
		int strLength = -1;
		for(int i=0;i<split.length-1;i++){
			String snew=split[i];
			strLength=strLength+snew.length()+1;
			split[i]=snew.concat(s.charAt(strLength)+"");
		}
		String regex="[.;:!?(){\n]";
		String lastString=split[split.length-1];
		if(lastString.matches(regex)){
			split[split.length-1]=lastString.concat(s.charAt(s.length()-1)+"");
		}
	}
	public void connectWithJdbc(){                                                                 //Connection With the database.
		try {
			conn=DriverManager.getConnection(connectionURL, "root", "");
			st = conn.createStatement();
		} catch (SQLException e) {
			System.err.println("There was an error while connecting to the JDBC. pleas check your sql server.");
		}
	}
	public void insertDataIntoDatabase(){
		for(data row:Data){
			try {
				st.executeUpdate("insert into sentences (row_id,sentence) values ('"+row.getId()+"','"+row.getData()+"');");
				st.executeUpdate("insert into task2 (sentence,checkbox_1,checkbox_2,checkbox_3,checkbox_4,checkbox_5,checkbox_6,checkbox_7,submit_count,skip_count) values ('"+row.getData()+"',0,0,0,0,0,0,0,0,0);");
			} catch (SQLException e) {
				e.printStackTrace();
			}
		}
	}
	public void chooseXlsxFile(){
		JFileChooser jfc=new JFileChooser();
		jfc.setFileSelectionMode(JFileChooser.FILES_AND_DIRECTORIES);
		int result=jfc.showOpenDialog(null);
		if(result==JFileChooser.CANCEL_OPTION){
			System.exit(1);
		}
		else if(result==JFileChooser.APPROVE_OPTION){
			filePath=jfc.getSelectedFile().getAbsolutePath();
		}
	}
	public void parser() {
		InputStream XlsxFileToRead = null;
		XSSFWorkbook workbook = null;
		try {
			XlsxFileToRead = new FileInputStream(filePath);
			workbook = new XSSFWorkbook(XlsxFileToRead);
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		XSSFSheet sheet = workbook.getSheetAt(0);
		XSSFRow row;
		XSSFCell cell;
		int j=0;
		Iterator rows = sheet.rowIterator();  
		int a=0;
		while (rows.hasNext()) {
			if(a==0){
				a++;
				rows.next();
				continue;
			}
			row = (XSSFRow) rows.next();
			Iterator cells = row.cellIterator();

			while (cells.hasNext()) {
				cell = (XSSFCell) cells.next();
				if (cell.getCellType() == XSSFCell.CELL_TYPE_STRING) {
					String s=cell.getStringCellValue();
					String[] split=s.split("[.;:!?(){\n]");
					punctuateStrings(s,split);
					for(int i=0;i<split.length;i++){
						String newString=split[i];
						if(newString.length()>3){                                            //to neglect comments of length smaller then 2
							if(newString.startsWith("\n")){
								newString=newString.substring(1,newString.length());        // to commit out the data that had a new line character at their start.
							}
							if(newString.endsWith("\n")){
								newString=newString.substring(0,newString.length()-1);
							}
							newString=newString.replace("'".charAt(0),'`');
							Data.add(new data(j, newString));
						}
					}
				} else if (cell.getCellType() == XSSFCell.CELL_TYPE_NUMERIC) {
					j=(int) cell.getNumericCellValue();
				}
			}
			try {workbook.getSheet("Sheet1");
				XlsxFileToRead.close();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
	}
	public static void main(String[] args){
		ReadXlsx read=new ReadXlsx();
		read.chooseXlsxFile();
		read.parser();
		read.connectWithJdbc();
		read.insertDataIntoDatabase();      
		JOptionPane.showMessageDialog(null, "The data has been populated into the database!");
	}
}