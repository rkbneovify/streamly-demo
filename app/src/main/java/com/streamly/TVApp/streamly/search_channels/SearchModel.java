package com.streamly.TVApp.streamly.search_channels;

public class SearchModel {
    String channelTitle;
    String logoUrl;
    String videoUrl;
    Long channelId;


    public SearchModel() {

    }

    public SearchModel(String channelTitle, String logoUrl) {
        this.channelTitle = channelTitle;
        this.logoUrl = logoUrl;
    }

    public SearchModel(String channelTitle, String logoUrl, String videoUrl) {
        this.channelTitle = channelTitle;
        this.logoUrl = logoUrl;
        this.videoUrl = videoUrl;
    }

    public SearchModel(long channelId, String channelTitle, String logoUrl, String videoUrl) {
        this.channelTitle = channelTitle;
        this.logoUrl = logoUrl;
        this.videoUrl = videoUrl;
        this.channelId = channelId;
    }

    public String getVideoUrl() {
        return videoUrl;
    }

    public void setVideoUrl(String videoUrl) {
        this.videoUrl = videoUrl;
    }

    public String getChannelTitle() {
        return channelTitle;
    }

    public void setChannelTitle(String channelTitle) {
        this.channelTitle = channelTitle;
    }

    public String getLogoUrl() {
        return logoUrl;
    }

    public void setLogoUrl(String logoUrl) {
        this.logoUrl = logoUrl;
    }

    public Long getChannelId() {
        return channelId;
    }
}
