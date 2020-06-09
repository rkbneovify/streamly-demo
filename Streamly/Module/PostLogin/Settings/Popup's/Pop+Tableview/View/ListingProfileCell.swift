//
//  ListingProfileCell.swift
//  Streamly
//
//  Created by Rohit Singh on 6/5/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class ListingProfileCell: UITableViewCell {

    // MARK: - Properties
    static let reuseIdentifier = "ListingProfileCell"
 

    // MARK: - Outlets
    @IBOutlet weak var headerLbl      : UILabel!
    @IBOutlet weak var dataLbl        : UILabel!

    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }

    // MARK: - Helpers
    func configureCell(_ header:String, _ data:String,flag:Bool)
    {
        self.headerLbl.text = header
        self.dataLbl.text = data
        self.dataLbl.font =  !flag ?  UIFont.init(name: "HelveticaNeue-Medium", size: 22.0) : UIFont.init(name: "HelveticaNeue-Light", size: 28.0)

    }

}
