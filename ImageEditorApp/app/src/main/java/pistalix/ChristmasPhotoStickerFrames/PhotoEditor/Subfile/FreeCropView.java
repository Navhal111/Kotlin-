package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Subfile;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.DashPathEffect;
import android.graphics.Paint;
import android.graphics.Paint.Style;
import android.graphics.Path;
import android.graphics.Point;
import android.os.Build.VERSION;
import android.util.DisplayMetrics;
import android.view.MotionEvent;
import android.view.ScaleGestureDetector;
import android.view.ScaleGestureDetector.SimpleOnScaleGestureListener;
import android.view.View;
import android.view.View.OnTouchListener;
import android.view.ViewGroup.LayoutParams;

import java.util.ArrayList;
import java.util.List;

public class FreeCropView extends View implements OnTouchListener {
    static Bitmap a;
    public static List<Point> b;
    int c;
    int d;
    int e = 2;
    int f;
    int g;
    boolean h = false;
    boolean i = false;
    boolean j = true;
    int k;
    int l;
    LayoutParams m;
    Context n;
    Point o = null;
    Point p = null;
    Paint q = new Paint();
    private ScaleGestureDetector r;
    private float s = 1.0f;
    private Paint t;

    private class a extends SimpleOnScaleGestureListener {
        public boolean onScale(ScaleGestureDetector scaleGestureDetector) {
            s = s * scaleGestureDetector.getScaleFactor();
            s = Math.max(0.1f, Math.min(s, 5.0f));
            invalidate();
            return true;
        }
    }

    public FreeCropView(Context context, Bitmap bitmap) {
        super(context);
        a = bitmap;
        this.l = a.getWidth();
        this.k = a.getHeight();
        System.out.println("img_width" + this.l + "img_height" + this.k);
        DisplayMetrics displayMetrics = getResources().getDisplayMetrics();
        this.g = displayMetrics.widthPixels;
        this.f = displayMetrics.heightPixels;
        if (this.l <= this.g) {
            this.d = this.g - this.l;
        }
        if (this.k <= this.f) {
            this.c = this.f - this.k;
        }
        this.n = context;
        setFocusable(true);
        setFocusableInTouchMode(true);
        this.t = new Paint(1);
        this.t.setStyle(Style.STROKE);
        this.t.setPathEffect(new DashPathEffect(new float[]{10.0f, 20.0f}, 5.0f));
        this.t.setStrokeWidth(5.0f);
        this.t.setColor(-1);
        if (VERSION.SDK_INT >= 11) {
            setLayerType(1, this.t);
        }
        this.t.setShadowLayer(5.5f, 6.0f, 6.0f, Integer.MIN_VALUE);
        this.m = new LayoutParams(a.getWidth(), a.getHeight());
        setOnTouchListener(this);
        b = new ArrayList();
        this.i = false;
        this.r = new ScaleGestureDetector(context, new a());
    }

    public static boolean a() {
        return true;
    }

    private boolean a(Point point, Point point2) {
        return point2.x + -3 < point.x && point.x < point2.x + 3 && point2.y - 3 < point.y && point.y < point2.y + 3 && b.size() >= 10;
    }

    public boolean getBooleanValue() {
        return this.h;
    }

    public void onDraw(Canvas canvas) {
        canvas.scale(this.s, this.s);
        canvas.drawBitmap(a, 0.0f, 0.0f, null);
        Path path = new Path();
        int i = 0;
        Object obj = 1;
        while (i < b.size()) {
            Object obj2;
            Point point = (Point) b.get(i);
            if (obj != null) {
                path.moveTo((float) point.x, (float) point.y);
                obj2 = null;
            } else if (i < b.size() - 1) {
                Point point2 = (Point) b.get(i + 1);
                path.quadTo((float) point.x, (float) point.y, (float) point2.x, (float) point2.y);
                obj2 = obj;
            } else {
                this.p = (Point) b.get(i);
                path.lineTo((float) point.x, (float) point.y);
                obj2 = obj;
            }
            i += 2;
            obj = obj2;
        }
        canvas.drawPath(path, this.t);
    }

    public boolean onTouch(View view, MotionEvent motionEvent) {
        Point point = new Point();
        point.x = (int) motionEvent.getX();
        point.y = (int) motionEvent.getY();
        if (this.j) {
            if (this.i) {
                if (a(this.o, point)) {
                    b.add(this.o);
                    this.j = false;
                    a();
                } else if (point.x <= this.l && point.y <= this.k) {
                    b.add(point);
                }
            } else if (point.x <= this.l && point.y <= this.k) {
                b.add(point);
            }
            if (!this.i) {
                this.o = point;
                this.i = true;
            }
        } else {
            this.r.onTouchEvent(motionEvent);
        }
        invalidate();
        if (motionEvent.getAction() == 1) {
            this.p = point;
            if (this.j && b.size() > 12 && !a(this.o, this.p)) {
                this.j = false;
                b.add(this.o);
                a();
            }
        }
        return true;
    }
}