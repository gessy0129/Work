import java.util.Random;

public class SampleRandom {
    public static void main(String[] args){
        Random rnd = new Random();
        int zero = 0;
        int one  = 0;
        int two  = 0;
        for (int i=0; i < 100000; i++) {
            switch (rnd.nextInt(3)) {
            case 0:
                zero++;
                break;
            case 1:
                one++;
                break;
            case 2:
                two++;
                break;
            }
        }
        System.out.println(zero);
        System.out.println(one);
        System.out.println(two);
    }
}
