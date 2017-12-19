package pistalix.crismasImage.editor.textdemo;

import java.io.Serializable;

public class StickerPropertyModel implements Serializable {

    private long stickerId;
    private String text;
    private float xLocation;
    private float yLocation;
    private float degree;
    private float scaling;
    private int order;
    private int horizonMirror;
    private String stickerURL;
    public int getHorizonMirror() {
        return horizonMirror;
    }
    public void setHorizonMirror(int horizonMirror) {
        this.horizonMirror = horizonMirror;
    }
    public String getStickerURL() {
        return stickerURL;
    }
    public void setStickerURL(String stickerURL) {
        this.stickerURL = stickerURL;
    }
    public long getStickerId() {
        return stickerId;
    }
    public void setStickerId(long stickerId) {
        this.stickerId = stickerId;
    }
    public String getText() {
        return text;
    }

    public void setText(String text) {
        this.text = text;
    }

    public float getxLocation() {
        return xLocation;
    }

    public void setxLocation(float xLocation) {
        this.xLocation = xLocation;
    }

    public float getyLocation() {
        return yLocation;
    }

    public void setyLocation(float yLocation) {
        this.yLocation = yLocation;
    }

    public float getDegree() {
        return degree;
    }

    public void setDegree(float degree) {
        this.degree = degree;
    }

    public float getScaling() {
        return scaling;
    }

    public void setScaling(float scaling) {
        this.scaling = scaling;
    }

    public int getOrder() {
        return order;
    }

    public void setOrder(int order) {
        this.order = order;
    }
}
