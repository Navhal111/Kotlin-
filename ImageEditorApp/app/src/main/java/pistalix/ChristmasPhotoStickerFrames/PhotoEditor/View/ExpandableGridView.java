package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.View;

import android.content.Context;
import android.util.AttributeSet;
import android.widget.GridView;

public class ExpandableGridView extends GridView {
    boolean a = false;

    public ExpandableGridView(Context context) {
        super(context);
    }

    public ExpandableGridView(Context context, AttributeSet attributeSet) {
        super(context, attributeSet);
    }

    public ExpandableGridView(Context context, AttributeSet attributeSet, int i) {
        super(context, attributeSet, i);
    }

    public boolean a() {
        return this.a;
    }

    public void onMeasure(int i, int i2) {
        if (a()) {
            super.onMeasure(i, MeasureSpec.makeMeasureSpec(536870911, Integer.MIN_VALUE));
            getLayoutParams().height = getMeasuredHeight();
            return;
        }
        super.onMeasure(i, i2);
    }

    public void setExpanded(boolean z) {
        this.a = z;
    }
}