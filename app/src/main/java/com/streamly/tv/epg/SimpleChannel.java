package com.streamly.tv.epg;

import android.text.Spanned;

import com.egeniq.androidtvprogramguide.entity.ProgramGuideChannel;

public class SimpleChannel implements ProgramGuideChannel {
     String  id;
    Spanned  name;
    String imageUrl;

    public SimpleChannel() {
    }

    public SimpleChannel(String id, Spanned name, String imageUrl) {
        this.id = id;
        this.name = name;
        this.imageUrl = imageUrl;
    }


    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public Spanned getName() {
        return name;
    }

    public void setName(Spanned name) {
        this.name = name;
    }

    public String getImageUrl() {
        return imageUrl;
    }

    public void setImageUrl(String imageUrl) {
        this.imageUrl = imageUrl;
    }
}
