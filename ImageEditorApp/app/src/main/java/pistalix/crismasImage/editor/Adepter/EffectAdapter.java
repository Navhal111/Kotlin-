package pistalix.crismasImage.editor.Adepter;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;

import java.util.ArrayList;

import pistalix.crismasImage.editor.R;
import pistalix.crismasImage.editor.modul.EffectModel;


/**
 * Created by a on 7/1/2017.
 */

public class EffectAdapter extends BaseAdapter{

    private Context context;
    ArrayList<EffectModel> effects;
    private LayoutInflater inflater;
    private ImageView image_effects;

    public EffectAdapter(Context context, ArrayList<EffectModel> effects) {
        this.context = context;
        this.effects = effects;
    }

    @Override
    public int getCount() {
        return  effects.size();
    }

    @Override
    public Object getItem(int i) {
        return effects.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        LayoutInflater inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        if (view == null){
            view = inflater.inflate(R.layout.effect_single,null);
        }
        ImageView image_effects = (ImageView) view.findViewById(R.id.image_effects);
        Bitmap bitmap = null;
        int resources = effects.get(i).getE_thumbId();
        bitmap = BitmapFactory.decodeResource(context.getResources(),resources);
        image_effects.setImageBitmap(bitmap);
        return view;
    }
}
