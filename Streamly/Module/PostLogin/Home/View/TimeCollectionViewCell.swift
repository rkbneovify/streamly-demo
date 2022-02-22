//
//  TimeCollectionViewCell.swift
//  Streamly
//
//  Created by Rohit Singh on 5/19/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class TimeCollectionViewCell: UICollectionViewCell {

    @IBOutlet weak var timeLabel: UILabel!
    var startTime = "12:00 AM"


    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        //  self.setup()
    }

    override init(frame: CGRect) {
        super.init(frame: frame)
        //  self.setup()
    }

    func setup() {
        self.layer.borderWidth = 0.5
        self.layer.borderColor = UIColor.clear.cgColor
    }

    func configureCell(indexInt:Int)
    {
        self.timeLabel.textAlignment = .left
         self.timeLabel.text  = ""

        if indexInt != 0
        {

            let formatter = DateFormatter()
            formatter.dateFormat = "hh:mm a"
            formatter.locale = Calendar.current.locale//Locale(identifier: "en_US_POSIX")
            formatter.timeZone = Calendar.current.timeZone
            let date1 = formatter.date(from: startTime)
            self.timeLabel.text = formatter.string(for: date1?.addingTimeInterval(TimeInterval((indexInt - 1)*30*60)))
        }
    }

}
