package excel;

public class data {
	private int id;
	private String data;
	
	public data(int i, String data){
		id=i;
		this.data=data;
	}
	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public String getData() {
		return data;
	}

	public void setData(String data) {
		this.data = data;
	}
}
