
package com.streamly.TVApp.streamly.channelsschedulepaginate;


import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


public class Program {

    @SerializedName("end")
    @Expose
    private Integer end;
    @SerializedName("start")
    @Expose
    private Integer start;
    @SerializedName("title")
    @Expose
    private String title;
    @SerializedName("description")
    @Expose
    private String description;

    public Integer getEnd() {
        return end;
    }

    public void setEnd(Integer end) {
        this.end = end;
    }

    public Integer getStart() {
        return start;
    }

    public void setStart(Integer start) {
        this.start = start;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

}
