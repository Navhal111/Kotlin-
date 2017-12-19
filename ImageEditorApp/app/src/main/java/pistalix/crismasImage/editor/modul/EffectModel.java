package pistalix.crismasImage.editor.modul;

/**
 * Created by a on 7/1/2017.
 */

public class EffectModel {
    private int e_thumbId;
    private int e_FrmId;

    public EffectModel(int e_thumbId, int e_FrmId) {
        this.e_thumbId = e_thumbId;
        this.e_FrmId = e_FrmId;
    }

    public int getE_thumbId() {
        return e_thumbId;
    }

    public void setE_thumbId(int e_thumbId) {
        this.e_thumbId = e_thumbId;
    }

    public int getE_FrmId() {
        return e_FrmId;
    }

    public void setE_FrmId(int e_FrmId) {
        this.e_FrmId = e_FrmId;
    }
}
