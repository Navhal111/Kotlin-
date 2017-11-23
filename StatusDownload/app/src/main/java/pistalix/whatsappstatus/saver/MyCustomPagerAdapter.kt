package pistalix.whatsappstatus.saver

import android.content.Context
import android.net.Uri
import android.support.v4.view.PagerAdapter
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.Toast
import com.bumptech.glide.Glide
import java.io.File

class MyCustomPagerAdapter(var context: Context,var images: ArrayList<File>) : PagerAdapter() {
    internal var layoutInflater: LayoutInflater


    init {
        layoutInflater = context.getSystemService(Context.LAYOUT_INFLATER_SERVICE) as LayoutInflater
    }

    override fun getCount(): Int {
        return images.size
    }

    override fun isViewFromObject(view: View, `object`: Any): Boolean {
        return view === `object` as LinearLayout
    }

    override fun instantiateItem(container: ViewGroup, position: Int): Any {
        val itemView = layoutInflater.inflate(R.layout.item, container, false)

        val imageView = itemView.findViewById<View>(R.id.imageView) as ImageView

        Glide.with(itemView.context).load(Uri.fromFile(File(images[position].toString()))).into(imageView)

        container.addView(itemView)

//        imageView.setOnClickListener { Toast.makeText(context, "you clicked image " + (position + 1), Toast.LENGTH_LONG).show() }

        return itemView
    }

    override fun destroyItem(container: ViewGroup, position: Int, `object`: Any) {
        container.removeView(`object` as LinearLayout)
    }
}