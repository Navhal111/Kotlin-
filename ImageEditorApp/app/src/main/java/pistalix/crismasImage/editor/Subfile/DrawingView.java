package pistalix.crismasImage.editor.Subfile;

import android.app.ProgressDialog;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Bitmap.Config;
import android.graphics.BitmapShader;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.DashPathEffect;
import android.graphics.Paint;
import android.graphics.Paint.Cap;
import android.graphics.Paint.Join;
import android.graphics.Paint.Style;
import android.graphics.Path;
import android.graphics.Point;
import android.graphics.PorterDuff.Mode;
import android.graphics.PorterDuffXfermode;
import android.graphics.Shader.TileMode;
import android.os.AsyncTask;
import android.util.AttributeSet;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnTouchListener;
import android.widget.ImageView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.LinkedList;
import java.util.Queue;
import java.util.Vector;

import pistalix.crismasImage.editor.R;


public class DrawingView extends ImageView implements OnTouchListener {
    Bitmap Bitmap2 = null;
    private int ERASE = 1;
    private int LASSO = 3;
    private int MODE = 1;
    private int NONE = 0;
    private int REDRAW = 4;
    private int TARGET = 2;
    private int TOLERANCE = 30;
    float X = 100.0f;
    float Y = 100.0f;
    private ActionListener actionListener;
    private int bpr = 200;
    private ArrayList<Integer> brushIndx = new ArrayList();
    private int brushSize = 18;
    private int brushSize1 = 18;
    Canvas c2;
    private ArrayList<Path> changesIndx = new ArrayList();
    Context ctx;
    private int curIndx = -1;
    private boolean drawLasso = false;
    private boolean drawOnLasso = true;
    Path drawPath = new Path();
    Paint erPaint = new Paint();
    Paint erPaint1 = new Paint();
    int erps = ImageUtils.dpToPx(getContext(), 2);
    private boolean insidCutEnable = true;
    private boolean isNewPath = false;
    private boolean isSelected = true;
    private boolean isTouched = false;
    Path lPath = new Path();
    private ArrayList<Boolean> lassoIndx = new ArrayList();
    private ArrayList<Integer> modeIndx = new ArrayList();
    private int offset = 200;
    private int offset1 = 200;
    private Bitmap orgBit;
    Paint paint = new Paint();
    private int pc;
    ProgressDialog pd;
    public Point point;
    float sX;
    float sY;
    float scale = 1.0f;
    Path tPath = new Path();
    private int targetBrushSize = 18;
    private int targetBrushSize1 = 18;
    private UndoRedoListener undoRedoListener;
    private boolean updateOnly = false;
    private ArrayList<Vector<Point>> vectorPoints = new ArrayList();
    Paint strokePaint;
    Bitmap Transparent;
    int r = 55;


    public interface ActionListener {
        void onActionCompleted(int i);
    }

    private class AsyncTaskRunner extends AsyncTask<Void, Void, Bitmap> {
        int ch;
        Vector<Point> targetPoints;

        public AsyncTaskRunner(int i) {
            this.ch = i;
        }

        protected Bitmap doInBackground(Void... voids) {
            if (this.ch != 0) {
                this.targetPoints = new Vector();
                FloodFill(DrawingView.this.Bitmap2, DrawingView.this.point, this.ch, 0);
                for (int k = 0; k < this.targetPoints.size(); k++) {
                    Point P = (Point) this.targetPoints.get(k);
                    DrawingView.this.Bitmap2.setPixel(P.x, P.y, 0);
                }
                DrawingView.this.changesIndx.add(DrawingView.this.curIndx + 1, new Path());
                DrawingView.this.brushIndx.add(DrawingView.this.curIndx + 1, Integer.valueOf(DrawingView.this.brushSize));
                DrawingView.this.modeIndx.add(DrawingView.this.curIndx + 1, Integer.valueOf(DrawingView.this.TARGET));
                DrawingView.this.vectorPoints.add(DrawingView.this.curIndx + 1, new Vector(this.targetPoints));
                DrawingView.this.lassoIndx.add(DrawingView.this.curIndx + 1, Boolean.valueOf(DrawingView.this.insidCutEnable));
                DrawingView.this.curIndx = DrawingView.this.curIndx + 1;
                clearNextChanges();
                DrawingView.this.updateOnly = true;
                Log.i("testing", "Time : " + this.ch + "  " + DrawingView.this.curIndx + "   " + DrawingView.this.changesIndx.size());
            }
            return null;
        }

        private void FloodFill(Bitmap bmp, Point pt, int targetColor, int replacementColor) {
            if (targetColor != 0) {
                Queue<Point> q = new LinkedList();
                q.add(pt);
                while (q.size() > 0) {
                    Point n = (Point) q.poll();
                    if (compareColor(bmp.getPixel(n.x, n.y), targetColor)) {
                        Point w = n;
                        Point e = new Point(n.x + 1, n.y);
                        while (w.x > 0 && compareColor(bmp.getPixel(w.x, w.y), targetColor)) {
                            bmp.setPixel(w.x, w.y, replacementColor);
                            this.targetPoints.add(new Point(w.x, w.y));
                            if (w.y > 0 && compareColor(bmp.getPixel(w.x, w.y - 1), targetColor)) {
                                q.add(new Point(w.x, w.y - 1));
                            }
                            if (w.y < bmp.getHeight() - 1 && compareColor(bmp.getPixel(w.x, w.y + 1), targetColor)) {
                                q.add(new Point(w.x, w.y + 1));
                            }
                            w.x--;
                        }
                        while (e.x < bmp.getWidth() - 1 && compareColor(bmp.getPixel(e.x, e.y), targetColor)) {
                            bmp.setPixel(e.x, e.y, replacementColor);
                            this.targetPoints.add(new Point(e.x, e.y));
                            if (e.y > 0 && compareColor(bmp.getPixel(e.x, e.y - 1), targetColor)) {
                                q.add(new Point(e.x, e.y - 1));
                            }
                            if (e.y < bmp.getHeight() - 1 && compareColor(bmp.getPixel(e.x, e.y + 1), targetColor)) {
                                q.add(new Point(e.x, e.y + 1));
                            }
                            e.x++;
                        }
                    }
                }
            }
        }

        public boolean compareColor(int color1, int color2) {
            if (color1 == 0 || color2 == 0) {
                return false;
            }
            if (color1 == color2) {
                return true;
            }
            int diffRed = Math.abs(Color.red(color1) - Color.red(color2));
            int diffGreen = Math.abs(Color.green(color1) - Color.green(color2));
            int diffBlue = Math.abs(Color.blue(color1) - Color.blue(color2));
            if (diffRed > DrawingView.this.TOLERANCE || diffGreen > DrawingView.this.TOLERANCE || diffBlue > DrawingView.this.TOLERANCE) {
                return false;
            }
            return true;
        }

        private void clearNextChanges() {
            int size = DrawingView.this.changesIndx.size();
            Log.i("testings", " Curindx " + DrawingView.this.curIndx + " Size " + size);
            int i = DrawingView.this.curIndx + 1;
            while (size > i) {
                Log.i("testings", " indx " + i);
                DrawingView.this.changesIndx.remove(i);
                DrawingView.this.brushIndx.remove(i);
                DrawingView.this.modeIndx.remove(i);
                DrawingView.this.vectorPoints.remove(i);
                DrawingView.this.lassoIndx.remove(i);
                size = DrawingView.this.changesIndx.size();
            }
            if (DrawingView.this.undoRedoListener != null) {
                DrawingView.this.undoRedoListener.enableUndo(true);
                DrawingView.this.undoRedoListener.enableRedo(false);
            }
            if (DrawingView.this.actionListener != null) {
                DrawingView.this.actionListener.onActionCompleted(DrawingView.this.MODE);
            }
        }

        protected void onPreExecute() {
            super.onPreExecute();
            DrawingView.this.pd = new ProgressDialog(DrawingView.this.getContext());
            DrawingView.this.pd.setMessage(DrawingView.this.ctx.getResources().getString(R.string.processing) + "...");
            DrawingView.this.pd.setCancelable(false);
            DrawingView.this.pd.show();
        }

        protected void onPostExecute(Bitmap v) {
            DrawingView.this.pd.dismiss();
            DrawingView.this.invalidate();
        }
    }

    public interface UndoRedoListener {
        void enableRedo(boolean z);

        void enableUndo(boolean z);
    }

    public void setUndoRedoListener(UndoRedoListener l) {
        this.undoRedoListener = l;
    }

    public void setActionListener(ActionListener l) {
        this.actionListener = l;
    }

    public DrawingView(Context context) {
        super(context);
        initPaint(context);
    }

    public DrawingView(Context context, AttributeSet attrs) {
        super(context, attrs);
        initPaint(context);
    }

    private void initPaint(Context context) {
        this.ctx = context;
        setFocusable(true);
        setFocusableInTouchMode(true);
        this.brushSize = ImageUtils.dpToPx(getContext(), this.brushSize);
        this.brushSize1 = ImageUtils.dpToPx(getContext(), this.brushSize);
        this.targetBrushSize = ImageUtils.dpToPx(getContext(), 50);
        this.targetBrushSize1 = ImageUtils.dpToPx(getContext(), 50);
        this.paint.setAlpha(0);
        this.paint.setColor(0);
        this.paint.setStyle(Style.STROKE);
        this.paint.setStrokeJoin(Join.ROUND);
        this.paint.setStrokeCap(Cap.ROUND);
        this.paint.setXfermode(new PorterDuffXfermode(Mode.CLEAR));
        this.paint.setAntiAlias(true);
        this.paint.setStrokeWidth(updatebrushsize(this.brushSize1, this.scale));
        this.erPaint = new Paint();
        this.erPaint.setAntiAlias(true);
        this.erPaint.setColor(-65536);
        this.erPaint.setAntiAlias(true);
        this.erPaint.setStyle(Style.STROKE);
        this.erPaint.setStrokeJoin(Join.MITER);
        this.erPaint.setStrokeWidth(updatebrushsize(this.erps, this.scale));
        this.erPaint1 = new Paint();
        this.erPaint1.setAntiAlias(true);
        this.erPaint1.setColor(-65536);
        this.erPaint1.setAntiAlias(true);
        this.erPaint1.setStyle(Style.STROKE);
        this.erPaint1.setStrokeJoin(Join.MITER);
        this.erPaint1.setStrokeWidth(updatebrushsize(this.erps, this.scale));
        this.erPaint1.setPathEffect(new DashPathEffect(new float[]{10.0f, 20.0f}, 0.0f));
    }

    public void setImageBitmap(Bitmap bm) {
        if (bm != null) {
            this.orgBit = bm.copy(Config.ARGB_8888, true);
            this.Bitmap2 = Bitmap.createBitmap(bm.getWidth(), bm.getHeight(), Config.ARGB_8888);
            this.c2 = new Canvas();
            this.c2.setBitmap(this.Bitmap2);
            this.c2.drawBitmap(bm, 0.0f, 0.0f, null);
            if (this.isSelected) {
                enableTouchClear(this.isSelected);
            }
            super.setImageBitmap(this.Bitmap2);
        }
    }

    protected void onDraw(Canvas canvas) {
        super.onDraw(canvas);
        if (this.c2 != null) {
            Paint p;
            Canvas canvas2;
            if (!this.updateOnly) {
                if (this.isTouched) {
                    this.paint = getPaintByMode(this.MODE, this.brushSize);
                    this.c2.drawPath(this.tPath, this.paint);
                    this.isTouched = false;
                } else if (this.curIndx >= 0 && this.drawOnLasso) {
                    redrawCanvas();
                }
            }
            if (this.MODE == this.TARGET) {
                p = new Paint();
                p.setColor(-65536);
                this.erPaint.setStrokeWidth(updatebrushsize(this.erps, this.scale));
                canvas.drawCircle(this.X, this.Y, (float) (this.targetBrushSize / 2), this.erPaint);
                canvas.drawCircle(this.X, this.Y + ((float) this.offset), updatebrushsize(ImageUtils.dpToPx(getContext(), 7), this.scale), p);
                p.setStrokeWidth(updatebrushsize(ImageUtils.dpToPx(getContext(), 1), this.scale));
                canvas2 = canvas;
                canvas2.drawLine(this.X - ((float) (this.targetBrushSize / 2)), this.Y, ((float) (this.targetBrushSize / 2)) + this.X, this.Y, p);
                canvas.drawLine(this.X, this.Y - ((float) (this.targetBrushSize / 2)), this.X, ((float) (this.targetBrushSize / 2)) + this.Y, p);
                this.drawOnLasso = true;
            }
            if (this.MODE == this.LASSO) {
                p = new Paint();
                p.setColor(-65536);
                this.erPaint.setStrokeWidth(updatebrushsize(this.erps, this.scale));
                canvas.drawCircle(this.X, this.Y, (float) (this.targetBrushSize / 2), this.erPaint);
                canvas.drawCircle(this.X, this.Y + ((float) this.offset), updatebrushsize(ImageUtils.dpToPx(getContext(), 7), this.scale), p);
                p.setStrokeWidth(updatebrushsize(ImageUtils.dpToPx(getContext(), 1), this.scale));
                canvas2 = canvas;
                canvas2.drawLine(this.X - ((float) (this.targetBrushSize / 2)), this.Y, ((float) (this.targetBrushSize / 2)) + this.X, this.Y, p);
                canvas.drawLine(this.X, this.Y - ((float) (this.targetBrushSize / 2)), this.X, ((float) (this.targetBrushSize / 2)) + this.Y, p);
                if (!this.drawOnLasso) {
                    this.erPaint1.setStrokeWidth(updatebrushsize(this.erps, this.scale));
                    canvas.drawPath(this.lPath, this.erPaint1);
                }
            }
            if (this.MODE == this.ERASE || this.MODE == this.REDRAW) {
                p = new Paint();
                p.setColor(-65536);
                this.erPaint.setStrokeWidth(updatebrushsize(this.erps, this.scale));
                canvas.drawCircle(this.X, this.Y, (float) (this.brushSize / 2), this.erPaint);
                canvas.drawCircle(this.X, this.Y + ((float) this.offset), updatebrushsize(ImageUtils.dpToPx(getContext(), 7), this.scale), p);
            }
            this.updateOnly = false;
        }
    }

    public boolean onTouch(View v, MotionEvent event) {
        if (this.MODE == this.TARGET) {
            this.drawOnLasso = false;
            this.X = event.getX();
            this.Y = event.getY() - ((float) this.offset);
            switch (event.getAction()) {
                case 0:
                    invalidate();
                    break;
                case 1:
                    if (this.X >= 0.0f && this.Y >= 0.0f && this.X <= ((float) this.Bitmap2.getWidth()) && this.Y <= ((float) this.Bitmap2.getHeight())) {
                        this.point = new Point((int) this.X, (int) this.Y);
                        this.pc = this.Bitmap2.getPixel((int) this.X, (int) this.Y);
                        new AsyncTaskRunner(this.pc).execute(new Void[0]);
                    }
                    invalidate();
                    break;
                case 2:
                    invalidate();
                    break;
            }
        }
        if (this.MODE == this.LASSO) {
            this.X = event.getX();
            this.Y = event.getY() - ((float) this.offset);
            switch (event.getAction()) {
                case 0:
                    this.isNewPath = true;
                    this.drawOnLasso = false;
                    this.sX = this.X;
                    this.sY = this.Y;
                    this.lPath = new Path();
                    this.lPath.moveTo(this.X, this.Y);
                    invalidate();
                    break;
                case 1:
                    this.lPath.lineTo(this.X, this.Y);
                    this.lPath.lineTo(this.sX, this.sY);
                    this.drawLasso = true;
                    invalidate();
                    if (this.actionListener != null) {
                        this.actionListener.onActionCompleted(5);
                        break;
                    }
                    break;
                case 2:
                    this.lPath.lineTo(this.X, this.Y);
                    invalidate();
                    break;
                default:
                    return false;
            }
        }
        if (this.MODE == this.ERASE || this.MODE == this.REDRAW) {
            this.X = event.getX();
            this.Y = event.getY() - ((float) this.offset);
            this.isTouched = true;
            switch (event.getAction()) {
                case 0:
                    this.paint.setStrokeWidth((float) this.brushSize);
                    this.tPath = new Path();
                    this.tPath.moveTo(this.X, this.Y);
                    this.drawPath.moveTo(this.X, this.Y);
                    invalidate();
                    break;
                case 1:
                    this.drawPath.lineTo(this.X, this.Y);
                    this.tPath.lineTo(this.X, this.Y);
                    invalidate();
                    this.changesIndx.add(this.curIndx + 1, new Path(this.tPath));
                    this.brushIndx.add(this.curIndx + 1, Integer.valueOf(this.brushSize));
                    this.modeIndx.add(this.curIndx + 1, Integer.valueOf(this.MODE));
                    this.vectorPoints.add(this.curIndx + 1, null);
                    this.lassoIndx.add(this.curIndx + 1, Boolean.valueOf(this.insidCutEnable));
                    this.tPath.reset();
                    this.curIndx++;
                    clearNextChanges();
                    break;
                case 2:
                    this.drawPath.lineTo(this.X, this.Y);
                    this.tPath.lineTo(this.X, this.Y);
                    invalidate();
                    break;
                default:
                    return false;
            }
        }
        return true;
    }

    private void clearNextChanges() {
        int size = this.changesIndx.size();
        Log.i("testings", "ClearNextChange Curindx " + this.curIndx + " Size " + size);
        int i = this.curIndx + 1;
        while (size > i) {
            Log.i("testings", " indx " + i);
            this.changesIndx.remove(i);
            this.brushIndx.remove(i);
            this.modeIndx.remove(i);
            this.vectorPoints.remove(i);
            this.lassoIndx.remove(i);
            size = this.changesIndx.size();
        }
        if (this.undoRedoListener != null) {
            this.undoRedoListener.enableUndo(true);
            this.undoRedoListener.enableRedo(false);
        }
        if (this.actionListener != null) {
            this.actionListener.onActionCompleted(this.MODE);
        }
    }

    public void setMODE(int m) {
        this.MODE = m;
    }

    private Paint getPaintByMode(int mode, int brushSize) {
        this.paint = new Paint();
        this.paint.setAlpha(0);
        this.paint.setStyle(Style.STROKE);
        this.paint.setStrokeJoin(Join.ROUND);
        this.paint.setStrokeCap(Cap.ROUND);
        this.paint.setAntiAlias(true);
        this.paint.setStrokeWidth((float) brushSize);
        if (mode == this.ERASE) {
            this.paint.setColor(0);
            this.paint.setXfermode(new PorterDuffXfermode(Mode.CLEAR));
        }
        if (mode == this.REDRAW) {
            this.paint.setColor(-1);
            this.paint.setShader(new BitmapShader(this.orgBit, TileMode.REPEAT, TileMode.REPEAT));
        }
        return this.paint;
    }

    public void setOffset(int os) {
        this.offset1 = os;
        this.offset = (int) updatebrushsize(os, this.scale);
        this.updateOnly = true;
    }

    public int getOffset() {
        return this.offset1;
    }

    public void setRadius(int r) {
        this.brushSize1 = ImageUtils.dpToPx(getContext(), r);
        this.brushSize = (int) updatebrushsize(this.brushSize1, this.scale);
        this.updateOnly = true;
    }

    public float updatebrushsize(int currentbs, float scale) {
        return ((float) currentbs) / scale;
    }

    public void updateOnScale(float scale) {
        Log.i("testings", "Scale " + scale + "  Brushsize  " + this.brushSize);
        this.scale = scale;
        this.brushSize = (int) updatebrushsize(this.brushSize1, scale);
        this.targetBrushSize = (int) updatebrushsize(this.targetBrushSize1, scale);
        this.offset = (int) updatebrushsize(this.offset1, scale);
    }

    public void setThreshold(int th) {
        this.TOLERANCE = th;
        if (this.curIndx >= 0) {
            Log.i("testings", " Threshold " + th + "  " + (((Integer) this.modeIndx.get(this.curIndx)).intValue() == this.TARGET));
        }
    }

    public boolean isTouchEnable() {
        return this.isSelected;
    }

    public void enableTouchClear(boolean b) {
        this.isSelected = b;
        if (b) {
            setOnTouchListener(this);
        } else {
            setOnTouchListener(null);
        }
    }

    public void enableInsideCut(boolean enable) {
        this.insidCutEnable = enable;
        if (this.isNewPath && this.drawLasso) {
            Log.i("testings", " draw lassso   on up ");
            drawLassoPath(this.lPath, this.insidCutEnable);
            this.changesIndx.add(this.curIndx + 1, new Path(this.lPath));
            this.brushIndx.add(this.curIndx + 1, Integer.valueOf(this.brushSize));
            this.modeIndx.add(this.curIndx + 1, Integer.valueOf(this.MODE));
            this.vectorPoints.add(this.curIndx + 1, null);
            this.lassoIndx.add(this.curIndx + 1, Boolean.valueOf(this.insidCutEnable));
            this.lPath.reset();
            this.curIndx++;
            clearNextChanges();
            this.drawOnLasso = false;
            invalidate();
            return;
        }
        Toast.makeText(this.ctx, this.ctx.getResources().getString(R.string.warn_draw_path), 0).show();
    }

    public void undoChange() {
        setImageBitmap(this.orgBit);
        Log.i("testings", "Performing UNDO Curindx " + this.curIndx + "  " + this.changesIndx.size());
        if (this.curIndx >= 0) {
            this.curIndx--;
            redrawCanvas();
            Log.i("testings", " Curindx " + this.curIndx + "  " + this.changesIndx.size());
            if (this.curIndx < 0 && this.undoRedoListener != null) {
                this.undoRedoListener.enableUndo(false);
            }
            if (this.undoRedoListener != null) {
                this.undoRedoListener.enableRedo(true);
            }
        }
    }

    public void redoChange() {
        Log.i("testings", (this.curIndx + 1 >= this.changesIndx.size()) + " Curindx " + this.curIndx + " " + this.changesIndx.size());
        if (this.curIndx + 1 < this.changesIndx.size()) {
            setImageBitmap(this.orgBit);
            this.curIndx++;
            redrawCanvas();
            if (this.curIndx + 1 >= this.changesIndx.size() && this.undoRedoListener != null) {
                this.undoRedoListener.enableRedo(false);
            }
            if (this.undoRedoListener != null) {
                this.undoRedoListener.enableUndo(true);
            }
        }
    }

    private void redrawCanvas() {
        int i = 0;
        while (i <= this.curIndx) {
            if (((Integer) this.modeIndx.get(i)).intValue() == this.ERASE || ((Integer) this.modeIndx.get(i)).intValue() == this.REDRAW) {
                this.tPath = new Path((Path) this.changesIndx.get(i));
                this.paint = getPaintByMode(((Integer) this.modeIndx.get(i)).intValue(), ((Integer) this.brushIndx.get(i)).intValue());
                this.c2.drawPath(this.tPath, this.paint);
                this.tPath.reset();
            }
            if (((Integer) this.modeIndx.get(i)).intValue() == this.TARGET) {
                Vector<Point> V = (Vector) this.vectorPoints.get(i);
                for (int k = 0; k < V.size(); k++) {
                    Point P = (Point) V.get(k);
                    this.Bitmap2.setPixel(P.x, P.y, 0);
                }
            }
            if (((Integer) this.modeIndx.get(i)).intValue() == this.LASSO) {
                Log.i("testings", " onDraw Lassoo ");
                drawLassoPath(new Path((Path) this.changesIndx.get(i)), ((Boolean) this.lassoIndx.get(i)).booleanValue());
            }
            i++;
        }
    }

    private void drawLassoPath(Path path, boolean insidCut) {
        Paint paint;
        if (insidCut) {
            paint = new Paint();
            paint.setAntiAlias(true);
            paint.setColor(0);
            paint.setXfermode(new PorterDuffXfermode(Mode.SRC_IN));
            this.c2.drawPath(path, paint);
        } else {
            Bitmap resultingImage = this.Bitmap2.copy(this.Bitmap2.getConfig(), true);
            new Canvas(resultingImage).drawBitmap(this.Bitmap2, 0.0f, 0.0f, null);
            this.c2.drawColor(this.NONE, Mode.CLEAR);
            paint = new Paint();
            paint.setAntiAlias(true);
            this.c2.drawPath(path, paint);
            paint.setXfermode(new PorterDuffXfermode(Mode.SRC_IN));
            this.c2.drawBitmap(resultingImage, 0.0f, 0.0f, paint);
        }
        this.drawLasso = false;
        this.drawOnLasso = true;
        this.isNewPath = false;
    }

    public Bitmap getFinalBitmap() {
        return this.Bitmap2;
    }
}